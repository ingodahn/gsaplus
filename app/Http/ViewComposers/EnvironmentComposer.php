<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App;

class EnvironmentComposer
{

    /**
     * The environment.
     */
    protected $environment;

    /**
     * Create a new composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->environment = App::environment();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if ($this->environment !== null) {
            $view->with('isProduction', $this->isProduction())
                ->with('isLocal', $this->isLocal());
        }
    }

    private function isProduction() {
        return ($this->environment ? ($this->environment == 'production') : false);
    }

    private function isLocal() {
        return ($this->environment ? ($this->environment == 'local') : false);
    }

}
