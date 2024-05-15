<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Comment;

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
        Gate::before(function(User $user){
            if ($user->role == 'MODERATOR') return true;
        });

        Gate::define('article', function(User $user, Article $article) {
            return $user->id == $article->user_id;
        });

        Gate::define('comment', function(User $user, Comment $comment){
            return $user->id == $comment->user_id;
        });

        Gate::define('comment-moderation', function(User $user){
            return $user->role == 'MODERATOR';
        });
    }
}
