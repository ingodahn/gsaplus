<?php

namespace App;

use Jenssegers\Date\Date;

class Helper {

    /**
     * Generate an array containing the days name and their numbers.
     */
    public static function generate_date_map()
    {
        $date = new Date('next Sunday');

        $days = array();

        for ($i = 0; $i < 7; $i++) {
            $days[$date->format('l')] = $i;
            $date = $date->add('1 day');
        }

        return $days;
    }
}

