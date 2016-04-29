<?php

namespace App\Console\Commands;

use App\Patient;
use App\Assignment;

use App\Models\InfoModel;
use Illuminate\Console\Command;

use Illuminate\Database\Eloquent\Collection;

/**
 * This command is only needed for testing and may be removed later on.
 *
 * It clears all unnecessary writing dates.
 *
 * Class ClearFutureWritingDates
 * @package App\Console\Commands
 */
class ClearDistantWritingDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsa:reassess-writing-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes distant writing dates';

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
        // !! the command has to use the actual date (not the test date)
        // -> don't attach middleware when entering route in routes.php
        foreach (Patient::all() as $patient) {
            $patient_week = $patient->patient_week();

            if ($patient_week == -1 || $patient->patient_week() >= 11) {
                continue;
            } else {
                // select assignment after next assignment
                // the collection starts with index 0
                // => index ($patient_week - 1) selects current assignment
                // => index ($patient_week) selects next assignment
                // => index ($patient_week + 1) selects assignments beyond the next assignment
                $index_for_next_week = min( max( $patient->patient_week + 1, 1 ), 12 );
                // select all assignments surpassing the next assignment
                $distant_assignments = $patient->ordered_assignments()->slice($index_for_next_week);
                // set writing dates to null
                $this->removeWritingDates($distant_assignments);
            }
        }
    }

    /**
     * Set writing dates to null.
     *
     * @param Collection $assignments
     *          assignments to process
     */
    protected function removeWritingDates(Collection $assignments) {
        foreach ($assignments as $assignment) {
            if ($assignment->writing_date) {
                print "Cleared writing date for patient ".$assignment->patient->name." (week ".$assignment->week.")\n";

                $assignment->writing_date = null;
                $assignment->save();
            }
        }
    }

}
