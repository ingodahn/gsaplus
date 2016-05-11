<?php

namespace App;

use Jenssegers\Date\Date;

use Illuminate\Database\Eloquent\Model;

class Helper {

    /**
     * Generate an array mapping each days name to its number.
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
     * Generate an array mapping each days number to its name.
     */
    public static function generate_day_number_map()
    {
        return array_flip(Helper::generate_day_name_map());
    }

    /**
     * Tell laravel to ignore timestamps (set to null) and mark entry as random (is_random = true).
     *
     * @param Model $model the target (e.g. an assignment)
     */
    public static function set_developer_attributes(Model &$model, $save = false) {
        $model->is_random = true;
        $model->timestamps = false;

        if ($save) {
            $model->save();
        }
    }

}

