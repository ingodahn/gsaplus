<?php

namespace App;

use App\Models\UserRole;

use Illuminate\Database\Eloquent\Model;

class Admin extends User
{

    protected static $singleTableType = UserRole::ADMIN;

    protected static $persisted = [];

}
