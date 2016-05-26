<?php

namespace App;

use App\Patient;
use App\TestSetting;

use App\Console\Commands\RemindUsersOfAssignment;
use App\Console\Commands\ClearDistantData;

use Artisan;

class CommandHelper
{

    public static function clearDistantData(Patient $patient = null) {
        $arguments = [];

        if ($patient) {
            $arguments['--'.ClearDistantData::OPTION_PATIENT] = $patient->name;
        }

        $args = array_merge($arguments, ['--quiet' => 'default']);

        $successful = (Artisan::call('gsa:clear-distant-data', $args) === 0);

        return $successful;
    }

    public static function sendReminders(...$options) {
        $settings = TestSetting::first();

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

        return $successful;
    }

    public static function sendAutomaticReminders() {
        $options = self::getReminderOptionsFromSettings();

        return sizeof($options) == 0 ?: self::sendReminders(...$options);
    }

    protected static function getReminderOptionsFromSettings() {
        $settings = TestSetting::first();

        $options = [];

        if ($settings->first_reminder) {
            $options[] = RemindUsersOfAssignment::OPTION_FIRST;
        }

        if ($settings->new_reminder) {
            $options[] = RemindUsersOfAssignment::OPTION_NEW;
        }

        if ($settings->due_reminder) {
            $options[] = RemindUsersOfAssignment::OPTION_DUE;
        }

        return $options;
    }

}