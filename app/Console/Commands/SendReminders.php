<?php

namespace App\Console\Commands;

use App\Assignment;
use App\Models\AssignmentStatus;
use App\Models\InfoModel;
use App\Models\PatientStatus;
use Illuminate\Console\Command;

use App\Patient;
use App\TestSetting;
use App\Helper;

use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Mail;

class SendReminders extends Command
{
    const OPTION_FIRST = 'first';
    const OPTION_NEW = 'new';
    const OPTION_DUE = 'due';
    const OPTION_MISSED = 'missed';
    const OPTION_ALL = 'all';
    const OPTION_SET_NEXT_WRITING_DATE = 'set-next-writing-date';

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
                                {--'.self::OPTION_ALL.' : Remind of first, new, due or missed assignment}
                                {--'.self::OPTION_SET_NEXT_WRITING_DATE.' : set next writing date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users of their progress';

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
        $this->info(Date::now().': call to gsa:send-reminders.');

        $settings = TestSetting::first();

        if ($settings && $settings->test_date) {
            Date::setTestNow($settings->test_date);
        }

        $this->sendReminders();
    }

    protected function sendReminders() {
        // get patients whose
        // - intervention didn't end
        // - hospital stay is over
        $patients = Patient::whereNull('intervention_ended_on')
            ->whereNotNull('date_from_clinics')
            ->get();

        if ($this->option(self::OPTION_FIRST) || $this->option(self::OPTION_ALL)) {
            $this->sendRemindersForOption(self::OPTION_FIRST, $patients);
        }

        if ($this->option(self::OPTION_NEW) || $this->option(self::OPTION_ALL)) {
            $this->sendRemindersForOption(self::OPTION_NEW, $patients);
        }

        if ($this->option(self::OPTION_DUE) || $this->option(self::OPTION_ALL)) {
            $this->sendRemindersForOption(self::OPTION_DUE, $patients);
        }

        if ($this->option(self::OPTION_MISSED) || $this->option(self::OPTION_ALL)) {
            $this->sendRemindersForOption(self::OPTION_MISSED, $patients);
        }
    }

    protected function sendRemindersForOption($option, $patients) {
        foreach ($patients as $patient) {
            $patient_status = $patient->status();

            if ($patient_status < PatientStatus::PATIENT_GOT_ASSIGNMENT) {
                // nothing to be done
                continue;
            }

            $patient_week = $patient->patient_week();
            $current_assignment = $patient->current_assignment();

            $status_condition = false;

            switch ($option) {
                case self::OPTION_FIRST:
                    $status_condition = $patient_status === PatientStatus::PATIENT_GOT_ASSIGNMENT &&
                                            $patient_week === 1 && !$current_assignment->notified_new;
                    break;
                case self::OPTION_NEW:
                    $status_condition = $patient_status === PatientStatus::PATIENT_GOT_ASSIGNMENT &&
                                            !$current_assignment->notified_new;
                    break;
                case self::OPTION_DUE:
                    $status_condition = $patient_status === PatientStatus::ASSIGNMENT_WILL_BECOME_DUE_SOON &&
                                            !$current_assignment->notified_due;
                    break;
                case self::OPTION_MISSED:
                    $status_condition = $patient_status === PatientStatus::PATIENT_MISSED_ASSIGNMENT &&
                                            $patient_week < 12 && !$current_assignment->notified_missed;
                    break;
            }

            // don't send reminder if no assignment is given / the patient already edited
            // the assignment / was reminded by system (depends on $option)
            if ($status_condition) {
                $this->sendEMail($patient, $option);

                // save date of reminder (don't send reminder twice...)
                if ($option === self::OPTION_DUE) {
                    $current_assignment->notified_due = true;
                } else if ($option === self::OPTION_MISSED) {
                    $current_assignment->notified_missed = true;
                } else {
                    $current_assignment->notified_new = true;
                }

                $current_assignment->save();
            }

            // set next writing date if the current assignment isn't the last
            // one and no future date is set
            $next_assignment = $patient->next_assignment();

            if ($this->option(self::OPTION_SET_NEXT_WRITING_DATE) &&
                $current_assignment->writing_date && $next_assignment && $next_assignment->writing_date === null) {
                $next_assignment->writing_date = $current_assignment->writing_date->startOfDay()->addWeek();
                $next_assignment->save();
            }
        }
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
        $parameters = ['PatientName' => $patient->name];

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
                $subject = 'Letzter Tagebucheintrag versäumt';

                $parameters['AssignmentDay'] = Helper::generate_day_number_map()[$patient->assignment_day];
                $parameters['NextWritingDate'] = $patient->next_assignment()->writing_date->format('d.m.Y');
                break;
        }

        if ($patient !== null && $view !== null) {
            Mail::send($view, $parameters,
                function ($message) use ($patient, $subject) {
                    $message->from(config('mail.team.address'), config('mail.team.name'))
                                ->to($patient->email, $patient->name)
                                ->subject($subject);
            });
        }

        if(count(Mail::failures()) > 0) {
            $this->info(Date::now().': failed to send reminder for '.$type_of_reminder.' assignment - patient: '.$patient->name.'.');
        } else {
            $this->info(Date::now().': sent reminder for '.$type_of_reminder.' assignment - patient: '.$patient->name.'.');
        }
    }
}
