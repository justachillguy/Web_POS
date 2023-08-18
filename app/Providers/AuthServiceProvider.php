<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Product;
use App\Models\User;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ProductPolicy::class => Product::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define("admin-only", function (User $user) {
            return $user->position === "admin";
        });

        // Gate::define("chgPw", function (User $user) {
        //     return $user->
        // });
    }
}
