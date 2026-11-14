<?php

namespace App\View\Composers;

use App\View\Navigation\NavigationMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavigationComposer
{
    public function __construct(
        protected NavigationMenu $menu
    ) {}

    public function compose(View $view): void
    {
        $view->with('navigation', $this->menu->for(Auth::user()));
    }
}
