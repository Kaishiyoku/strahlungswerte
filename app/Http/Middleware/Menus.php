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
            ->link('locations.index,locations.show', getHeroiconSvgImageHtml('s-globe') . __('common.nav.home'))
            ->link('statistics.index', getHeroiconSvgImageHtml('s-chart-bar') . __('common.nav.statistics'));

        if ($isLoggedIn) {
            \LaravelMenu::register('user')
                ->dropdownIf($isAdministrator, getHeroiconSvgImageHtml('s-user-circle') . $this->auth->user()->name, \LaravelMenu::dropdownContainer()
                    ->header(__('common.nav.administration'))
                    ->link('horizon.index', getHeroiconSvgImageHtml('s-chart-square-bar') . __('common.nav.horizon'))
                )
                ->link('logout', getHeroiconSvgImageHtml('s-logout') . __('common.nav.logout'))
                ->content('<a class="navbar-link" data-toggle="dark-mode"></a>');
        } else {
            \LaravelMenu::register('user')
                ->link('login', getHeroiconSvgImageHtml('s-login') . __('common.nav.login'))
                ->content('<a class="navbar-link" data-toggle="dark-mode"></a>');
        }

        return $next($request);
    }
}
