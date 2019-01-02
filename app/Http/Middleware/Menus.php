<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Kaishiyoku\Menu\Config\Config;
use Kaishiyoku\Menu\Facades\Menu;
use MikeAlmond\Color\Color;

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
        Menu::setConfig(Config::forBootstrap4());

        $isLoggedIn = $this->auth->check();
        $isAdministrator = $isLoggedIn && $this->auth->user()->is_administrator;

        $administrationLinks = removeNulls(itemIf([
            Menu::dropdownHeader(__('common.nav.administration')),
            Menu::link('/horizon', '<i class="fas fa-compass"></i> ' . __('common.nav.horizon')),
        ], $isAdministrator, []));

        Menu::registerDefault([
            Menu::linkRoute('locations.index', '<i class="fas fa-globe-europe"></i> ' . __('common.nav.home'), [], [], ['locations.show']),
        ], ['class' => 'navbar-nav mr-auto']);

        if ($isLoggedIn) {
            Menu::register('user', [
                Menu::dropdown(array_merge([

                ], $administrationLinks, [
                    Menu::dropdownDivider(),
                    Menu::linkRoute('logout', '<i class="fas fa-sign-out-alt"></i> ' . __('common.nav.logout'), [], ['data-click' => '#logout-form']),
                ]), '<i class="fas fa-user"></i> ' . $this->auth->user()->name . ' ', null, [], ['class' => 'dropdown-menu-right'])
            ], ['class' => 'navbar-nav']);
        } else {
            Menu::register('user', [
                Menu::linkRoute('login', '<i class="fas fa-sign-in-alt"></i> ' . __('common.nav.login')),
            ], ['class' => 'navbar-nav']);
        }

        return $next($request);
    }
}
