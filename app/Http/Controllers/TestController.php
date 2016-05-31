<?php

namespace App\Http\Controllers;

use App\CommandHelper;
use App\Models\UserRole;
use App\User;
use App\Patient;
use App\Assignment;

use App\Helper;

use App\TestSetting;

use Illuminate\Support\Facades\Auth;

use Jenssegers\Date\Date;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Requests;
use UxWeb\SweetAlert\SweetAlert as Alert;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{

    // store settings across method calls
    private $settings = null;

    public function showOverview() {
        $infos = ['patient' => new Collection, 'therapist' => new Collection, 'admin' => new Collection];

        foreach (User::all() as $user) {
            $info = $user->info();

            if ($user->type === UserRole::PATIENT) {
                $current_assignment = $user->current_assignment();

                if ($current_assignment && $current_assignment->writing_date) {
                    $info['dateOfReminder'] = $current_assignment->writing_date
                            ->copy()->addDays(config('gsa.reminder_period_in_days'))->format('d.m.Y');
                    $info['dateOfDeadline'] = $current_assignment->writing_date
                        ->copy()->addDays(config('gsa.missed_period_in_days'))->format('d.m.Y');
                }
            }

            $infos[$user->type]->push($info);
        }

        foreach ($infos as $key => $info) {
            $infos[$key] = $info->sortBy('name');
        }

        $settings = TestSetting::first()->info();
        // set current date if none was saved before
        $settings['testDate'] = array_get($settings, 'testDate', '');

        return view('test.overview')->with('infos', $infos)->with('settings', $settings);
    }

    public function loginAs(Request $request, User $user) {
        Auth::login($user);

        return Redirect::to('/Home');
    }

    public function setAssignmentRelatedTestDate(Request $request, Patient $patient) {
        $next_assignment = $patient->next_assignment();

        if ($next_assignment && $next_assignment->writing_date) {
            $this->setDateAndSendReminders($request, $next_assignment->writing_date->copy());
        }

        return Redirect::back();
    }

    public function setDateOfCurrentReminder(Request $request, Patient $patient) {
        $current_assignment = $patient->current_assignment();

        if ($current_assignment && $current_assignment->writing_date) {
            $this->setDateAndSendReminders($request, $current_assignment->writing_date->copy()
                    ->addDays(config('gsa.reminder_period_in_days')));
        }

        return Redirect::back();
    }

    public function setDateOfCurrentDeadline(Request $request, Patient $patient) {
        $current_assignment = $patient->current_assignment();

        if ($current_assignment && $current_assignment->writing_date) {
            $this->setDateAndSendReminders($request, $current_assignment->writing_date->copy()
                ->addDays(config('gsa.missed_period_in_days')));
        }

        return Redirect::back();
    }
    public function setRelativeTestDate(Request $request) {
        $relative_date_string = $request->input('relative_date_string');

        $settings = $this->settings();

        if ($settings->test_date) {
            Date::setTestNow($settings->test_date);
        }

        $this->setDateAndSendReminders($request, Date::parse($relative_date_string));

        return Redirect::back();
    }

    protected function setDateAndSendReminders(Request $request, $date) {
        $settings = $this->settings();

        // backup test date
        $date_backup = Date::getTestNow() ?: null;
        // unset test date
        Date::setTestNow();
        // get current date
        $actual_date = Date::now();

        // clearing data makes no sense if test date isn't set - 2 cases
        // 1. new date is in the future
        // 2. new date is in the past
        //      => data shouldn't be cleared - all future writing dates would be missing
        //      when returning to actual date
        $should_clear_data = $settings->test_date && $date->lt($settings->test_date) && $date->gt($actual_date);

        // restore test date
        if ($date_backup) {
            Date::setTestNow($date_backup);
        }

        $settings->test_date = $date;
        $save_successful = $settings->save();

        $clear_successful = !$should_clear_data || CommandHelper::clearDistantData();
        $reminders_successful = CommandHelper::sendAutomaticReminders();

        if ($save_successful && $clear_successful && $reminders_successful) {
            Alert::success('Das Datum wurde erfolgreich auf den '.
                $settings->test_date->format('d.m.Y').' geändert. ')->persistent();
        } else {
            if (!$save_successful) {
                Alert::warning('Die Einstellungen konnten leider nicht gespeichert werden.')->persistent();
            } else if (!$clear_successful) {
                Alert::warning('Nicht alle Daten konnten bereinigt werden.')->persistent();
            } else if (!$reminders_successful) {
                Alert::warning('Nicht alle Benachrichtigungen konnten versendet werden.')->persistent();
            } else {
                Alert::warning('Das Datum konnte leider nicht geändert werden.')->persistent();
            }
        }

        if ($should_clear_data && $request->exists('remove-distant-data')) {
            Alert::warning('Die Bereinigung wurde erfolgreich ausgeführt.')->persistent();
        }
    }

    public function changeSettings(Request $request) {
        if ($request->exists('save_settings')) {
            $this->saveSettings($request);
        } else if ($request->exists('reset_settings')) {
            $this->restoreSettings();
        }

        return Redirect::back();
    }

    protected function saveSettings(Request $request) {
        $settings = $this->settings();

        $settings->first_reminder = $request->input('first_reminder', '0');
        $settings->new_reminder = $request->input('new_reminder', '0');
        $settings->due_reminder = $request->input('due_reminder', '0');
        $settings->calc_next_writing_date = $request->input('calc_next_writing_date', '0');

        $successful = $settings->save();

        if ($request->has('test_date')) {
            $this->setDateAndSendReminders($request, Date::createFromFormat('d.m.Y', $request->input('test_date')));
        } else if ($successful) {
            Alert::success('Die neuen Einstellungen wurden gespeichert.')->persistent();
        } else {
            Alert::warning('Die neuen Einstellungen konnten leider nicht gespeichert werden.')->persistent();
        }

        return Redirect::back();
    }

    protected function restoreSettings() {
        $settings = $this->settings();

        $settings->fill(factory(TestSetting::class)->make()->toArray());

        if ($settings->save()) {
            Alert::success('Die Einstellungen wurden erfolgreich zurück gesetzt.')->persistent();
        } else {
            Alert::warning('Die Einstellungen konnten leider nicht zurück gesetzt werden.')->persistent();
        }

        CommandHelper::clearDistantData();
    }

    public function sendReminders($option) {
        return $this->sendRemindersFor($option);
    }

    public function sendRemindersFor(...$options) {
        $successful = CommandHelper::sendReminders(...$options);

        if ($successful) {
            Alert::success('Alle Benachrichtigungen wurden verschickt.')->persistent();
        } else {
            Alert::warning('Nicht alle Benachrichtigungen konnten verschickt werden.')->persistent();
        }

        return Redirect::back();
    }

    public function dumpInfo(User $user) {
        return view('test.info-dump')->with('info', $this->getInfo($user));
    }

    protected function insertNameOfAssignmentDay(&$info, $day_map) {
        if (array_key_exists('assignmentDay', $info)) {
            $info['assignmentDay'] = $day_map[$info['assignmentDay']];
        }
    }

    protected function getInfo(User $user) {
        $info = [];
        $day_map = Helper::generate_day_number_map();

        switch ($user->type) {
            case UserRole::PATIENT:
                $info = $user->all_info();
                $this->insertNameOfAssignmentDay($info, $day_map);
                break;
            case UserRole::THERAPIST:
                $info = $user->info_with('patients');

                if (array_key_exists('patients', $info)) {
                    for ($count = 0; $count < count($info['patients']); $count++) {
                        $this->insertNameOfAssignmentDay($info['patients'][$count], $day_map);
                    }
                }
                break;
            case UserRole::ADMIN:
                $info = $user->info();
        }

        return $info;
    }

    public function saveDumpToLogFile(Request $request, User $user) {
        $fileName = $user->name.'_'.date('Y-m-d_G_i_s').'.log';
        $filePath = 'dumps/'.$fileName;

        $settings = $this->settings();

        $test_date = $settings->test_date ? $settings->test_date : Date::createFromFormat('Y-m-d', date('Y-m-d'));
        $date_string = $test_date->format('d.m.Y');

        if (Storage::put($filePath, "Test date is ".$date_string."\n\n".var_export($this->getInfo($user), true))) {
            Alert::success('Die aktuellen Daten unter "'.$fileName.'" gespeichert.')->persistent();
        } else {
            Alert::warning('Leider konnte kein Abbild gespeichert werden.')->persistent();
        }

        return Redirect::back();
    }

    public function clearDistantData() {
        $successful = CommandHelper::clearDistantData();

        if ($successful) {
            Alert::success('Alle inkonsisten Daten wurden entfernt.')->persistent();
        } else {
            Alert::warning('Nicht alle Daten konnten bereinigt werden.')->persistent();
        }

        return Redirect::back();
    }

    protected function settings() {
        if ($this->settings === null) {
            $this->settings = TestSetting::first();
        }

        return $this->settings;
    }

}
