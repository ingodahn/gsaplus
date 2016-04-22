<?php

namespace App\Http\Controllers;

use App\User;
use App\Patient;
use App\Assignment;

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

class TestController extends Controller
{

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
        $settings['testDate'] = array_get($settings, 'testDate', Date::now()->format('d.m.Y.'));

        return view('test.overview')->with('infos', $infos)->with('settings', $settings);
    }

    public function loginAs(Request $request, User $user) {
        Auth::login($user);

        return Redirect::to('/Home');
    }

    public function setAssignmentRelatedTestDate(Patient $patient, $daysToAdd = 0) {
        $next_assignment = $patient->next_assignment();

        if ($next_assignment && $next_assignment->writing_date) {
            $settings = TestSetting::first();
            $settings->test_date = $next_assignment->writing_date->copy()->addDays($daysToAdd);

            if ($settings->save()) {
                Alert::success('Das Datum wurde erfolgreich auf den '.
                    $settings->test_date->format('d.m.Y').' geändert. ')->persistent();

                $this->sendAutomaticReminders();
            } else {
                Alert::error('Der Patient hat entweder keine Folgeaufgabe oder das nächste Schreibdatum '.
                    'wurde noch nicht gesetzt.', 'Das Datum konnte nicht geändert werden.')->persistent();
            }
        }

        return Redirect::back();
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
        $settings = TestSetting::first();

        if ($request->has('test_date')) {
            $settings->test_date = Date::createFromFormat('d.m.Y', $request->input('test_date'));
        }

        $settings->first_reminder = $request->input('first_reminder', '0');
        $settings->new_reminder = $request->input('new_reminder', '0');
        $settings->due_reminder = $request->input('due_reminder', '0');

        if ($settings->save()) {
            Alert::success('Die neuen Einstellungen wurden gespeichert.')->persistent();
        } else {
            Alert::error('Die neuen Einstellungen konnten leider nicht gespeichert werden.')->persistent();
        }
    }

    protected function restoreSettings() {
        $settings = TestSetting::first();

        $settings->fill(factory(TestSetting::class)->make()->toArray());

        if ($settings->save()) {
            Alert::success('Die Einstellungen wurden erfolgreich zurück gesetzt.')->persistent();
        } else {
            Alert::error('Die Einstellungen konnten leider nicht zurück gesetzt werden.')->persistent();
        }
    }

    protected function sendAutomaticReminders() {
        $settings = TestSetting::first();

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
        $successful = true;

        foreach ($options as $option) {
            $successful = (Artisan::call('gsa:send-reminders', ['--'.$option => 'default', '--quiet' => 'default']) === 0) && $successful;
        }

        if ($successful) {
            Alert::success('Alle Benachrichtigungen wurden verschickt.')->persistent();
        } else {
            Alert::error('Nicht alle Benachrichtigungen konnten verschickt werden.')->persistent();
        }

        return Redirect::back();
    }

}
