<?php

namespace App;

use Jenssegers\Date\Date;

class Helper {

    /**
     * Generate an array containing the days name and their numbers.
     */
    public static function generate_day_name_map()
    {
        $date = new Date('next Sunday');

        $days = array();

        // valid days: Sunday ... Thursday
        foreach (range(0,4) as $i) {
            $days[$date->format('l')] = $i;
            $date = $date->add('1 day');
        }

        return $days;
    }

    /**
     * Generate an array containing the days name and their numbers.
     */
    public static function generate_day_number_map()
    {
        return array_flip(Helper::generate_day_name_map());
    }

}

