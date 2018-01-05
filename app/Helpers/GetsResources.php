<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Article;

trait GetsResources
{
    /**
     * Retrieve article by its slug
     * @param  string $slug
     * @return \App\Models\Article
     */
    protected function getArticleBySlug(string $slug)
    {
        return Article::where('slug', $slug)->first();
    }

    /**
     * Retrieve user by their username
     * @param  string $username
     * @return \App\Models\User
     */
    protected function getUserByName(string $username)
    {
        return User::whereUsername($username)->first();
    }
}
