<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AdminLteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Sin menu de usuario en el navbar
        });
    }
}
