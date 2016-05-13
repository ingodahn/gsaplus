<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\User;
use App\Patient;
use App\Assignment;

use App\Helper;

use App\TestSetting;

use App\Console\Commands\RemindUsersOfAssignment;

use Illuminate\Support\Facades\Auth;

use Jenssegers\Date\Date;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Requests;
use UxWeb\SweetAlert\SweetAlert as Alert;
use Artisan;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{

    // store settings accross method calls
    private $settings = null;

    public function showOverview() {
        $infos = ['patient' => new Collection, 'therapist' => new Collection, 'admin' => new Collection];

        foreach (User::all() as $user) {
            $infos[$user->type]->push($user->info());
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

    public function setAssignmentRelatedTestDate(Patient $patient, $daysToAdd = 0) {
        $next_assignment = $patient->next_assignment();

        if ($next_assignment && $next_assignment->writing_date) {
            $this->setDateAndSendReminders($next_assignment->writing_date->copy()->addDays($daysToAdd));
        }

        return Redirect::back();
    }

    public function setRelativeTestDate(Request $request) {
        $relative_date_string = $request->input('relative_date_string');

        $settings = $this->settings();

        if ($settings->test_date) {
            Date::setTestNow($settings->test_date);
        }

        $this->setDateAndSendReminders(Date::parse($relative_date_string));

        Date::setTestNow();

        return Redirect::back();
    }

    protected function setDateAndSendReminders($date) {
        $settings = $this->settings();

        $settings->test_date = $date;
        $save_successful = $settings->save();

        if ($save_successful && $this->sendAutomaticReminders()) {
            Alert::success('Das Datum wurde erfolgreich auf den '.
                $settings->test_date->format('d.m.Y').' geändert. ')->persistent();
        } else {
            if ($save_successful) {
                Alert::error('Nicht alle Benachrichtigungen konnten versendet werden.')->persistent();
            } else {
                Alert::warning('Das Datum konnte leider nicht geändert werden.')->persistent();
            }
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

        if ($request->has('test_date')) {
            $settings->test_date = Date::createFromFormat('d.m.Y', $request->input('test_date'));
        }

        $settings->first_reminder = $request->input('first_reminder', '0');
        $settings->new_reminder = $request->input('new_reminder', '0');
        $settings->due_reminder = $request->input('due_reminder', '0');
        $settings->calc_next_writing_date = $request->input('calc_next_writing_date', '0');

        $successful = $settings->save();

        if ($request->has('test_date')) {
            $this->sendAutomaticReminders();
        }

        if ($successful) {
            Alert::success('Die neuen Einstellungen wurden gespeichert.')->persistent();
        } else {
            Alert::error('Die neuen Einstellungen konnten leider nicht gespeichert werden.')->persistent();
        }
    }

    protected function restoreSettings() {
        $settings = $this->settings();

        $settings->fill(factory(TestSetting::class)->make()->toArray());

        if ($settings->save()) {
            Alert::success('Die Einstellungen wurden erfolgreich zurück gesetzt.')->persistent();
        } else {
            Alert::error('Die Einstellungen konnten leider nicht zurück gesetzt werden.')->persistent();
        }
    }

    protected function sendAutomaticReminders() {
        $settings = $this->settings();

        $reminders = [];

        if ($settings->first_reminder) {
            $reminders[] = RemindUsersOfAssignment::OPTION_FIRST;
        }

        if ($settings->new_reminder) {
            $reminders[] = RemindUsersOfAssignment::OPTION_NEW;
        }

        if ($settings->due_reminder) {
            $reminders[] = RemindUsersOfAssignment::OPTION_DUE;
        }

        return sizeof($reminders) == 0 ?: $this->sendRemindersFor(...$reminders);
    }

    public function sendReminders($option) {
        return $this->sendRemindersFor($option);
    }

    public function sendRemindersFor(...$options) {
        $settings = $this->settings();

        $successful = true;

        foreach ($options as $option) {
            $arguments = ['--'.$option => 'default',
                            '--quiet' => 'default'];

            if ($settings->calc_next_writing_date) {
                $arguments['--'.RemindUsersOfAssignment::OPTION_SET_NEXT_WRITING_DATE] = 'default';
            }

            $successful = (Artisan::call('gsa:send-reminders',
                                $arguments) === 0) && $successful;
        }

        if ($successful) {
            Alert::success('Alle Benachrichtigungen wurden verschickt.')->persistent();
        } else {
            Alert::error('Nicht alle Benachrichtigungen konnten verschickt werden.')->persistent();
        }

        return Redirect::back();
    }

    protected function dumpInfo(User $user) {
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
                for ($count = 0; $count < count($info['patients']); $count++) {
                    $this->insertNameOfAssignmentDay($info['patients'][$count], $day_map);
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
            Alert::error('Leider konnte kein Abbild gespeichert werden.')->persistent();
        }

        return Redirect::back();
    }

    public function clearDistantWritingDates() {
        if (Artisan::call('gsa:reassess-writing-dates', ['--quiet' => 'default']) === 0) {
            Alert::success('Die Schreibdaten wurden erfolgreich bereinigt.')->persistent();
        } else {
            Alert::error('Die Schreibdaten konnten leider nicht bereinigt werden.')->persistent();
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
