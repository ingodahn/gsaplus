<?php

namespace App;

use App\Models\InfoModel;

class WeekDay extends InfoModel
{
    public $timestamps = false;

    protected $primaryKey = 'number';
}
