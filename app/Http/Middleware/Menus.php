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
            ->link('locations.index,locations.show', getFontAwesomeSvgImageHtml('solid', 'globe-europe') . __('common.nav.home'))
            ->link('statistics.index', getFontAwesomeSvgImageHtml('solid', 'chart-line') . __('common.nav.statistics'));

        if ($isLoggedIn) {
            \LaravelMenu::register('user')
                ->dropdownIf($isAdministrator, getFontAwesomeSvgImageHtml('solid', 'user') . $this->auth->user()->name, \LaravelMenu::dropdownContainer()
                    ->header(__('common.nav.administration'))
                    ->link('horizon.index', getFontAwesomeSvgImageHtml('solid', 'compass') . __('common.nav.horizon'))
                )
                ->link('logout', getFontAwesomeSvgImageHtml('solid', 'sign-out-alt') . __('common.nav.logout'))
                ->content('<a class="navbar-link" data-toggle="dark-mode"></a>');
        } else {
            \LaravelMenu::register('user')
                ->link('login', getFontAwesomeSvgImageHtml('solid', 'sign-in-alt') . __('common.nav.login'))
                ->content('<a class="navbar-link" data-toggle="dark-mode"></a>');
        }

        return $next($request);
    }
}
