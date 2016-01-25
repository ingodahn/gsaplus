<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeekDay extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'number';
}
