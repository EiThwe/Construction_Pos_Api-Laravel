<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Policies\PermissionPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('checkPermission', function ($user, $roleString) {
            if (Auth::user()->role === "admin") {
                return true; // Admin has all permissions
            } else {
                if (empty($roleString)) return false;

                if ($roleString === "all") return true;

                $roles = explode(",", $roleString);

                return in_array(Auth::user()->role, $roles);
            }
        });
    }
}
