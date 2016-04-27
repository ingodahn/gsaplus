<?php

namespace App\Console\Commands;

use App\Assignment;
use App\Models\AssignmentStatus;
use App\Models\InfoModel;
use Illuminate\Console\Command;

use App\Patient;
use App\TestSetting;

use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Mail;


class RemindUsersOfAssignment extends Command
{
    const OPTION_FIRST = 'first';
    const OPTION_NEW = 'new';
    const OPTION_DUE = 'due';
    const OPTION_MISSED = 'missed';
    const OPTION_ALL = 'all';

    const VIEW_DIR = 'emails.assignment';

    protected $views = [self::OPTION_FIRST => self::VIEW_DIR.'.first',
                        self::OPTION_NEW => self::VIEW_DIR.'.new',
                        self::OPTION_DUE => self::VIEW_DIR.'.due',
                        self::OPTION_MISSED => self::VIEW_DIR.'.missed'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsa:send-reminders
                                {--'.self::OPTION_FIRST.' : Remind of first assignment}
                                {--'.self::OPTION_NEW.' : Remind of new assignment}
                                {--'.self::OPTION_DUE.' : Remind of due assignment}
                                {--'.self::OPTION_MISSED.' : Remind of missed assignment}
                                {--'.self::OPTION_ALL.' : Remind of first, new, due or missed assignment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users of their first, new, due or missed assignment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $settings = TestSetting::first();

        if ($settings && $settings->test_date) {
            Date::setTestNow($settings->test_date);
        }

        if ($this->option(self::OPTION_FIRST) || $this->option(self::OPTION_ALL)) {
            $this->sendRemindersForNewOrCurrentAssignments(self::OPTION_FIRST);
        }

        if ($this->option(self::OPTION_NEW) || $this->option(self::OPTION_ALL)) {
            $this->sendRemindersForNewOrCurrentAssignments(self::OPTION_NEW);
        }

        if ($this->option(self::OPTION_DUE) || $this->option(self::OPTION_ALL)) {
            $this->sendRemindersForDueAssignments();
        }

        if ($this->option(self::OPTION_MISSED) || $this->option(self::OPTION_ALL)) {
            $this->sendRemindersForMissedAssignments();
        }

        print "\r";
    }

    protected function sendRemindersForNewOrCurrentAssignments($type_of_reminder) {
        // get all assignments with writing_date = now
        $assignments = Assignment::where('writing_date', '=',
                        Date::now()->format('Y-m-d'))->get();

        $bar = $this->output->createProgressBar($assignments->count());

        $bar->setFormat("Notifying of {$type_of_reminder} assignment: ".'[%bar%] %current%/%max%');
        $bar->start();

        // remind of first or current assignment
        foreach ($assignments as $assignment) {
            $patient = $assignment->patient;

            if ($patient->intervention_ended_on === null) {
                $next_assignment = $patient->assignment_for_week($assignment->week + 1);

                if ($next_assignment && $next_assignment->writing_date === null) {
                    $next_assignment->writing_date = Date::now()->startOfDay()->addWeek();
                    $next_assignment->save();
                }

                // don't send reminder if patient already edited the assignment
                if ($assignment->status() === AssignmentStatus::PATIENT_GOT_ASSIGNMENT) {
                    if ($assignment->week === 1 && $type_of_reminder == self::OPTION_FIRST) {
                        // remind of first assignment
                        $this->sendEMail($assignment->patient, self::OPTION_FIRST);
                    } else if ($assignment->week > 1 && $assignment->week <= 12
                        && $type_of_reminder == self::OPTION_NEW
                        && $assignment->problem != NULL) {
                        // remind of current assignment
                        $this->sendEMail($assignment->patient, self::OPTION_NEW);
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $bar->clear();
    }

    protected function sendRemindersForDueAssignments() {
        // get patients whose
        // - intervention didn't end
        // - hospital stay is over
        $patients = Patient::whereNull('intervention_ended_on')
            ->whereNotNull('date_from_clinics')
            ->get();

        $bar = $this->output->createProgressBar($patients->count());

        $bar->setFormat("Notifying of due assignment: ".'[%bar%] %current%/%max%');
        $bar->start();

        foreach ($patients as $patient) {
            // current assignment may be null if patient is in week 0
            // (patient left the clinic but the first assignments writing
            // date is in the future)

            // the current assignment always has a writing date (in fact the latest
            // assignment with writing date < current date is returned - see docs in
            // class Patient)
            $current_assignment = $patient->current_assignment();

            // don't send reminder if no assignment is given / the patient already edited
            // the assignment
            if ($current_assignment &&
                $current_assignment->status() <= AssignmentStatus::PATIENT_EDITED_ASSIGNMENT) {
                $end_of_period = $current_assignment->writing_date->copy()
                    ->addDays(config('gsa.reminder_period_in_days'));

                if (Date::now()->gte($end_of_period) &&
                    ($current_assignment->week != 12 || $current_assignment->writing_date->copy()->addWeek()->isFuture())) {
                    // remind of due assignment if 5 days passed since the writing date
                    $this->sendEMail($patient, self::OPTION_DUE);

                    // save date of reminder
                    $current_assignment->date_of_reminder = Date::now();
                    $current_assignment->save();
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $bar->clear();
    }

    protected function sendRemindersForMissedAssignments() {

    }

    /*
     * Sends a reminder to the given patient. The reminders type may be
     * 'first', 'new', 'due' or 'missed'.
     *
     * This method is called multiple times if reminders should be
     * sent for different types.
     */
    protected function sendEMail(Patient $patient, $type_of_reminder) {
        $view = $this->views[$type_of_reminder];

        $subject = null;

        switch ($type_of_reminder) {
            case self::OPTION_FIRST:
                $subject = 'Erster Schreibimpuls gegeben';
                break;
            case self::OPTION_NEW:
                $subject = 'Neuer Schreibimpuls vorhanden';
                break;
            case self::OPTION_DUE:
                $subject = 'Letzter Schreibimpuls unbeantwortet';
                break;
            case self::OPTION_MISSED:
                $subject = 'Letzten Tagebucheintrag versäumt';
                break;
        }

        if ($patient !== null && $view !== null) {
            Mail::send($view, [],
                function ($message) use ($patient, $subject) {
                    $message->from(config('mail.team.address'), config('mail.team.name'))
                                ->to($patient->email, $patient->name)
                                ->subject($subject);
            });
        }
    }
}
