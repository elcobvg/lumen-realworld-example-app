<?php

namespace App\Models;

use Auth;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Jenssegers\Mongodb\Eloquent\Model;
use App\RealWorld\Follow\Followable;
use App\RealWorld\Favorite\HasFavorite;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, Followable, HasFavorite;

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
     * JWT token string
     * @var string
     */
    protected $token;

    /**
     * Get all the articles by the user.
     *
     * @return \Jenssegers\Mongodb\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id')->latest();
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
     * Get all the articles of the following users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feed()
    {
        $followingIds = $this->following()->pluck('id')->toArray();

        return Article::loadRelations()->whereIn('user_id', $followingIds);
    }

    /**
     * Generate a JWT token for the user.
     *
     * @return string
     */
    public function getTokenAttribute()
    {
        // return JWTAuth::fromUser($this);
        return Auth::guard()->tokenById($this->id);
        // return $this->token;
    }

    /**
     * Generate a JWT token for the user.
     *
     * @param string $token
     */
    public function setTokenAttribute(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
