<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Menus
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isLoggedIn = $this->auth->check();
        $isAdministrator = $isLoggedIn && $this->auth->user()->is_administrator;

        \LaravelMenu::register()
            ->addClassNames(['mr-auto'])
            ->link('locations.index,locations.show', '<i class="fas fa-globe-europe"></i> ' . __('common.nav.home'));

        if ($isLoggedIn) {
            \LaravelMenu::register('user')
                ->dropdownIf($isAdministrator, '<i class="fas fa-user"></i> ' . $this->auth->user()->name, \LaravelMenu::dropdownContainer()
                    ->header(__('common.nav.administration'))
                    ->link('horizon.index', '<i class="fas fa-compass"></i> ' . __('common.nav.horizon'))
                )
                ->link('logout', '<i class="fas fa-sign-out-alt"></i> ' . __('common.nav.logout'))
            ;
        } else {
            \LaravelMenu::register('user')
                ->link('login', '<i class="fas fa-sign-in-alt"></i> ' . __('common.nav.login'));
        }

        return $next($request);
    }
}
