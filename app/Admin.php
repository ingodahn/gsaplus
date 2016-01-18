<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{

    /**
     * Get the appropriate user instance.
     */
    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }

}
