<?php

namespace App\Models;

use JWTAuth;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'bio',
        'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Generate a JWT token for the user.
     *
     * @return string
     */
    public function getTokenAttribute()
    {
        return JWTAuth::fromUser($this);
    }

    /**
     * Get all the articles by the user.
     *
     * @return \Jenssegers\Mongodb\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class)->latest();
    }

    /**
     * Get all the comments by the user.
     *
     * @return \Jenssegers\Mongodb\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Get the users who follow this user.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, null, 'following', 'followers');
    }

    /**
     * Get the users whom this user is following.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function following()
    {
        return $this->belongsToMany(User::class, null, 'followers', 'following');
    }

    /**
     * Get the favorite articles of this user.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get all the articles of the following users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feed()
    {
        $followingIds = $this->following()->pluck('id')->toArray();

        return Article::loadRelations()->whereIn('user_id', $followingIds);
    }
}
