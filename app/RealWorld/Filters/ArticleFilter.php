<?php

namespace App\RealWorld\Filters;

use App\Models\Tag;
use App\Models\User;
use App\Models\Article;

class ArticleFilter extends Filter
{
    /**
     * Filter by author username.
     * Get all the articles by the user with given username.
     *
     * @param $username
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function author($username)
    {
        $user = User::whereUsername($username)->first();
        $user_id = $user ? $user->id : null;

        return $this->collection->where('author_id', $user_id);
    }

    /**
     * Filter by favorited username.
     * Get all the articles favorited by the user with given username.
     *
     * @param $username
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function favorited($username)
    {
        $user = User::whereUsername($username)->first();
        return $user->favorites->intersect($this->collection);
    }

    /**
     * Filter by tag name.
     * Get all the articles tagged by the given tag name.
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function tag($name)
    {
        $articles = Article::whereRaw(['tags.name' => $name])->get();
        return $articles->intersect($this->collection);
    }
}
