<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('update-article', function ($user, $article) {
            return $user->id === $article->author_id;
        });

        Gate::define('delete-article', function ($user, $article) {
            return $user->id === $article->author_id;
        });

        Gate::define('delete-comment', function ($user, $comment) {
            return $user->id === $comment->author_id;
        });

        Gate::define('favorite-article', function ($user, $article) {
            return $user->id != $article->author_id;
        });
    }
}
