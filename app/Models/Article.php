<?php

namespace App\Models;

use App\RealWorld\Slug\HasSlug;
use App\RealWorld\Filters\Filterable;
use App\RealWorld\Favorite\Favoritable;
use Jenssegers\Mongodb\Eloquent\Model;

class Article extends Model
{
    use Favoritable, Filterable, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'description',
        'body',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'author'
    ];

    /**
     * Get the author of the article.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all the comments for the article.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function comments()
    {
        return $this->embedsMany(Comment::class)->latest();
    }

    /**
     * Get all the tags that belong to the article.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tags()
    {
        return $this->embedsMany(Tag::class);
    }

    /**
     * Get the key name for route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the attribute name to slugify.
     *
     * @return string
     */
    public function getSlugSourceColumn()
    {
        return 'title';
    }

    /**
     * Get list of values which are not allowed for this resource
     *
     * @return array
     */
    public function getBannedSlugValues()
    {
        return ['feed'];
    }
}
