<?php

namespace App\RealWorld\Favorite;

use Auth;
use App\Models\User;

trait Favoritable
{
    /**
     * Check if the authenticated user has favorited the article.
     * We make use of lazy loading if the relationship is not already loaded.
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

        return $this->favorited()->count();
    }

    /**
     * Get the users that favorited the article.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function favorited()
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
        return !! $this->favorited()->where('favorited_by_ids', $user->id)->count();
    }
}
