<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        'App\News' => 'App\Policies\NewsPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Type' => 'App\Policies\TypePolicy',
        'App\Role' => 'App\Policies\RolePolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Category' => 'App\Policies\CategoryPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-analytics', function() {

        });

        Gate::define('edit-profile', function($user) {
            //return 
        });

        Gate::define('edit-comment', function($user, $comment) {
            return $user->id == $comment->user_id;
        });

        Gate::define('view-comments', function($user) {
            return in_array('Commentator', $user->roles()->pluck('role_name')->toArray()) || in_array('Super Admin', $user->roles()->pluck('role_name')->toArray());
        });
    }
}
