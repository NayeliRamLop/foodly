<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate para verificar si el usuario es admin
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        // Gate adicional para verificar si el usuario es un usuario normal
        Gate::define('user', function (User $user) {
            return $user->isUser();
        });

        // Gate para verificar si el usuario puede gestionar otros usuarios
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate para verificar si el usuario puede gestionar recetas de otros
        Gate::define('manage-recipes', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate para verificar si el usuario puede ver estadísticas
        Gate::define('view-stats', function (User $user) {
            return $user->role === 'admin';
        });
    }
}