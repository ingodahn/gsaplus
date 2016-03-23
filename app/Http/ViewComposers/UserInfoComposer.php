<?php

namespace App\Http\ViewComposers;

use App\Models\UserRole;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Auth\Guard;

class UserInfoComposer
{

    /**
     * The logged in user.
     *
     * @var \App\User
     */
    protected $user;

    /**
     * Create a new composer.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        // Dependencies automatically resolved by service container...
        $this->user = $auth->user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if ($this->user !== null) {
            $view->with('Role', $this->user->type)
                ->with('Name', $this->user->name)
                ->with('isTherapist', $this->isTherapist())
                ->with('isPatient', $this->isPatient())
                ->with('isAdmin', $this->isAdmin())
                ->with('isLoggedIn', true);
        } else {
          $view->with('isLoggedIn', false);
        }
    }

    private function isTherapist() {
        return ($this->user->type === UserRole::THERAPIST);
    }

    private function isPatient() {
        return ($this->user->type === UserRole::PATIENT);
    }

    private function isAdmin() {
        return ($this->user->type === UserRole::ADMIN);
    }

}
