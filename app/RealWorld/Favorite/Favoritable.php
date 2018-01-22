<?php

namespace App\RealWorld\Favorite;

use Auth;
use App\Models\User;

trait Favoritable
{
    /**
     * Check if the authenticated user has favorited the article.
     *
     * @return bool
     */
    public function getFavoritedAttribute()
    {
        if (! Auth::check() || ! $this->favorited_by_ids) {
            return false;
        }
        return in_array(Auth::user()->id, $this->favorited_by_ids, true);
    }

    /**
     * Get the favorites count of the article.
     *
     * @return integer
     */
    public function getFavoritesCountAttribute()
    {
        if (array_key_exists('favorited_count', $this->getAttributes())) {
            return $this->favorited_count;
        }
        return $this->favoritedBy()->count();
    }

    /**
     * Get the users that favorited the article.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, null, 'favorite_article_ids', 'favorited_by_ids');
    }

    /**
     * Check if the article is favorited by the given user.
     *
     * @param User $user
     * @return bool
     */
    public function isFavoritedBy(User $user)
    {
        return !! $this->favoritedBy()->where('favorited_by_ids', $user->id)->count();
    }
}
