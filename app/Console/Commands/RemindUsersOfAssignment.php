<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Patient;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class RemindUsersOfAssignment extends Command
{
    const OPTION_FIRST = "first";
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
    }

    protected function sendRemindersForNewOrCurrentAssignments($type_of_reminder) {
        // get patients whose
        // - intervention didn't end
        // - hospital stay is over
        // - assignment day matches the current day
        $patients = Patient::whereNull('intervention_ended_on')
            ->whereNotNull('date_from_clinics')
            ->where('assignment_day', '=', Carbon::now()->dayOfWeek)->get();

        // remind of first or current assignment
        foreach ($patients as $patient) {
            $week = $patient->patient_week();

            if ($week === 1 && $type_of_reminder == self::OPTION_FIRST) {
                // remind of first assignment
                $this->sendEMail($patient, self::OPTION_FIRST);
            } else if ($week <= 12 && $type_of_reminder == self::OPTION_NEW) {
                // remind of current assignment
                $this->sendEMail($patient, self::OPTION_NEW);
            }
        }
    }

    protected function sendRemindersForDueAssignments() {

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
            case self::OPTION_NEW:
                $subject = 'Neue Aufgabe vorhanden';
                break;
            case self::OPTION_DUE:
                $subject = 'Letzte Aufgabe unbearbeitet';
                break;
            case self::OPTION_MISSED:
                $subject = 'Letzte Aufgabe versäumt';
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
