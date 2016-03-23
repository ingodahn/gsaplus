<?php

namespace App;

use App\Models\UserRole;

class Admin extends User
{

    protected static $singleTableType = UserRole::ADMIN;

    protected static $persisted = [];

}
