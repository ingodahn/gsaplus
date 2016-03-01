<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

use App\Patient;
use App\Admin;
use App\Therapist;

class User extends Authenticatable
{
    use SingleTableInheritanceTrait;

    protected $table = "users";

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [Patient::class, Admin::class, Therapist::class];

    protected static $persisted = ['name', 'email', 'password', 'last_login', 'is_random'];

    protected $dates = ['created_at', 'updated_at', 'last_login' ,
                            'registration_date', 'date_from_clinics', 'last_activity'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

}
