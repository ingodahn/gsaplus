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

class SendNotifications extends Command
{
    const OPTION_REGISTRATION_SUCCESSFUL = 'successful-registration';
    const OPTION_FIRST_ASSIGNMENT = 'first-assignment';
    const OPTION_NEW_ASSIGNMENT = 'new-assignment';
    const OPTION_DUE_ASSIGNMENT = 'due-assignment';
    const OPTION_MISSED_ASSIGNMENT = 'missed-assignment';
    const OPTION_INTERVENTION_END = 'intervention-end';
    const OPTION_ALL = 'all';
    const OPTION_SET_NEXT_WRITING_DATE = 'set-next-writing-date';

    const VIEW_DIR = 'emails';

    protected $views = [self::OPTION_REGISTRATION_SUCCESSFUL => self::VIEW_DIR.'.confirm_registration',
                        self::OPTION_FIRST_ASSIGNMENT => self::VIEW_DIR.'.assignment.first',
                        self::OPTION_NEW_ASSIGNMENT => self::VIEW_DIR.'.assignment.new',
                        self::OPTION_DUE_ASSIGNMENT => self::VIEW_DIR.'.assignment.due',
                        self::OPTION_MISSED_ASSIGNMENT => self::VIEW_DIR.'.assignment.missed',
                        self::OPTION_INTERVENTION_END => self::VIEW_DIR.'.complete_intervention'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsa:send-notifications
                                {--'.self::OPTION_FIRST_ASSIGNMENT.' : Notify of first assignment}
                                {--'.self::OPTION_NEW_ASSIGNMENT.' : Notify of new assignment}
                                {--'.self::OPTION_DUE_ASSIGNMENT.' : Notify of due assignment}
                                {--'.self::OPTION_MISSED_ASSIGNMENT.' : Notify of missed assignment}
                                {--'.self::OPTION_INTERVENTION_END.' : Notify of intervention end}
                                {--'.self::OPTION_REGISTRATION_SUCCESSFUL.' : Notify of successful registration}
                                {--'.self::OPTION_ALL.' : Send all notifications}
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
        $this->info(Date::now().': call to gsa:send-notifications.');

        $settings = TestSetting::first();

        if ($settings && $settings->test_date) {
            Date::setTestNow($settings->test_date);
        }

        $this->sendNotifications();
    }

    protected function sendNotifications() {
        // get patients whose
        // - intervention didn't end
        // - hospital stay is over
        $patients = Patient::whereNull('intervention_ended_on')
            ->whereNotNull('date_from_clinics')
            ->get();

        if ($this->option(self::OPTION_FIRST_ASSIGNMENT) || $this->option(self::OPTION_ALL)) {
            $this->sendNotificationsForOption(self::OPTION_FIRST_ASSIGNMENT, $patients);
        }

        if ($this->option(self::OPTION_NEW_ASSIGNMENT) || $this->option(self::OPTION_ALL)) {
            $this->sendNotificationsForOption(self::OPTION_NEW_ASSIGNMENT, $patients);
        }

        if ($this->option(self::OPTION_DUE_ASSIGNMENT) || $this->option(self::OPTION_ALL)) {
            $this->sendNotificationsForOption(self::OPTION_DUE_ASSIGNMENT, $patients);
        }

        if ($this->option(self::OPTION_MISSED_ASSIGNMENT) || $this->option(self::OPTION_ALL)) {
            $this->sendNotificationsForOption(self::OPTION_MISSED_ASSIGNMENT, $patients);
        }

        if ($this->option(self::OPTION_INTERVENTION_END) || $this->option(self::OPTION_ALL)) {
            $this->sendNotificationsForOption(self::OPTION_INTERVENTION_END, $patients);
        }

        if ($this->option(self::OPTION_REGISTRATION_SUCCESSFUL) || $this->option(self::OPTION_ALL)) {
            $this->sendNotificationsForOption(self::OPTION_REGISTRATION_SUCCESSFUL, $patients);
        }
    }

    protected function sendNotificationsForOption($option, $patients) {
        foreach ($patients as $patient) {
            $patient_status = $patient->status();

            if ($patient_status < PatientStatus::PATIENT_LEFT_CLINIC) {
                // nothing to be done
                continue;
            }

            $patient_week = $patient->patient_week();
            $current_assignment = $patient->current_assignment();

            $status_condition = false;

            switch ($option) {
                case self::OPTION_REGISTRATION_SUCCESSFUL:
                    $status_condition = $patient_status === PatientStatus::PATIENT_LEFT_CLINIC &&
                                            !$patient->confirmed_registration;
                    break;
                case self::OPTION_FIRST_ASSIGNMENT:
                    $status_condition = $patient_status === PatientStatus::PATIENT_GOT_ASSIGNMENT &&
                                            $patient_week === 1 && !$current_assignment->notified_new;
                    break;
                case self::OPTION_NEW_ASSIGNMENT:
                    $status_condition = $patient_status === PatientStatus::PATIENT_GOT_ASSIGNMENT &&
                                            !$current_assignment->notified_new;
                    break;
                case self::OPTION_DUE_ASSIGNMENT:
                    $status_condition = $patient_status === PatientStatus::ASSIGNMENT_WILL_BECOME_DUE_SOON &&
                                            !$current_assignment->notified_due;
                    break;
                case self::OPTION_MISSED_ASSIGNMENT:
                    $status_condition = $patient_status === PatientStatus::PATIENT_MISSED_ASSIGNMENT &&
                                            $patient_week < 12 && !$current_assignment->notified_missed;
                    break;
                case self::OPTION_INTERVENTION_END:
                    $status_condition = $patient_status === PatientStatus::INTERVENTION_ENDED &&
                                            !$patient->notified_of_intervention_end;
                    break;
            }

            // don't send notification if no assignment is given / the patient already edited
            // the assignment / was already notified by system (depends on $option)
            if ($status_condition) {
                $this->sendEMail($patient, $option);

                // save if notification has been sent (avoid duplicates)
                if ($this->isAssignmentRelatedOption($option)) {
                    if ($option === self::OPTION_DUE_ASSIGNMENT) {
                        $current_assignment->notified_due = true;
                    } else if ($option === self::OPTION_MISSED_ASSIGNMENT) {
                        $current_assignment->notified_missed = true;
                    } else if ($option === self::OPTION_FIRST_ASSIGNMENT || $option === self::OPTION_NEW_ASSIGNMENT) {
                        $current_assignment->notified_new = true;
                    }
                    $current_assignment->save();
                } else {
                    if ($option === self::OPTION_INTERVENTION_END) {
                        $patient->notified_of_intervention_end = true;
                    } else if ($option === self::OPTION_REGISTRATION_SUCCESSFUL) {
                        $patient->confirmed_registration = true;
                    }
                    $patient->save();
                }
            }

            // set next writing date if the current assignment isn't the last
            // one and no future date is set
            $next_assignment = $patient->next_assignment();

            if ($this->option(self::OPTION_SET_NEXT_WRITING_DATE) && $current_assignment &&
                $current_assignment->writing_date && $next_assignment && $next_assignment->writing_date === null) {
                $next_assignment->writing_date = $current_assignment->writing_date->startOfDay()->addWeek();
                $next_assignment->save();
            }
        }
    }

    /*
     * Sends a notification to the given patient.
     *
     * This method is called multiple times if notifications should be
     * sent for different types.
     */
    protected function sendEMail(Patient $patient, $type_of_notification) {
        $view = $this->views[$type_of_notification];

        $subject = null;
        $parameters = ['PatientName' => $patient->name];

        switch ($type_of_notification) {
            case self::OPTION_REGISTRATION_SUCCESSFUL:
                $subject = 'Ihre Registrierung';
                break;
            case self::OPTION_FIRST_ASSIGNMENT:
                $subject = 'Erster Schreibimpuls gegeben';
                break;
            case self::OPTION_NEW_ASSIGNMENT:
                $subject = 'Neuer Schreibimpuls vorhanden';
                break;
            case self::OPTION_DUE_ASSIGNMENT:
                $subject = 'Letzter Schreibimpuls unbeantwortet';
                break;
            case self::OPTION_MISSED_ASSIGNMENT:
                $subject = 'Letzter Tagebucheintrag versäumt';

                $parameters['AssignmentDay'] = Helper::generate_day_number_map()[$patient->assignment_day];
                $parameters['NextWritingDate'] = $patient->next_assignment()->writing_date->format('d.m.Y');
                break;
            case self::OPTION_INTERVENTION_END:
                $subject = 'Ende der Online-Nachsorge';

                $parameters['PatientCode'] = $patient->code;
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
            $this->info(Date::now().': failed to send notification for '.$this->getLogString($type_of_notification).' - patient: '.$patient->name.'.');
        } else {
            $this->info(Date::now().': sent notification for '.$this->getLogString($type_of_notification).' - patient: '.$patient->name.'.');
        }
    }

    protected function getLogString($option) {
        switch ($option) {
            case self::OPTION_REGISTRATION_SUCCESSFUL:
                return "successful registration";
            case self::OPTION_FIRST_ASSIGNMENT:
                return "first assignment";
            case self::OPTION_NEW_ASSIGNMENT:
                return "new assignment";
            case self::OPTION_DUE_ASSIGNMENT:
                return "due assignment";
            case self::OPTION_MISSED_ASSIGNMENT:
                return "missed assignment";
            case self::OPTION_INTERVENTION_END:
                return "intervention end";
        }
    }

    protected function isAssignmentRelatedOption($option) {
        switch ($option) {
            case self::OPTION_FIRST_ASSIGNMENT:
            case self::OPTION_NEW_ASSIGNMENT:
            case self::OPTION_DUE_ASSIGNMENT:
            case self::OPTION_MISSED_ASSIGNMENT:
                return true;
            default:
                return false;
        }
    }
}
