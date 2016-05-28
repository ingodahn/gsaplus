<?php

return [

    /*
    |--------------------------------------------------------------------------
    | TODO - missing title
    |--------------------------------------------------------------------------
    |
    | TODO: write description
    |
    */

    'intervention_period_in_weeks' => env('INTERVENTION_PERIOD', 12),

    /*
    |--------------------------------------------------------------------------
    | TODO - missing title
    |--------------------------------------------------------------------------
    |
    | TODO: write description
    |
    */

    'reminder_period_in_days' => env('REMINDER_PERIOD', 3),
    'missed_period_in_days' => env('MISSED_PERIOD', 5),
    'buffer_between_assignments' => env('BUFFER_BETWEEN_ASSIGNMENTS', 5),

    // for testing: change current date
    'current_date' => env('CURRENT_DATE', 'now'),

];
