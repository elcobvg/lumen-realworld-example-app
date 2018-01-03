<?php

namespace App\Models;

use Illuminate\Support\Str;
use Jenssegers\Mongodb\Eloquent\Model;

class Article extends Model
{
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
     * Create a new Article instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->slug = $this->title ?: 'tmp';
    }

    /**
     * Get the author of the article.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class);
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
     * Get the users who favorited the article.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class, null, 'favorites', 'favorited_by');
    }

    /**
     * [setSlugAttribute description]
     * @param string $value [description]
     */
    public function setSlugAttribute(string $value)
    {
        $this->attributes['slug'] = $this->generateUniqueSlug($value);
    }

    /**
     * [generateUniqueSlug description]
     * @param  string $title [description]
     * @return [type]        [description]
     */
    private function generateUniqueSlug(string $title)
    {
        $temp = Str::slug($title, '-');
        /*
        if (Article::where('slug', 'exists', $temp)) {
            $i = 1;
            $newslug = $temp . '-' . $i;
            while (Article::where('slug', 'exists', $newslug)) {
                $i++;
                $newslug = $temp . '-' . $i;
            }
            $temp =  $newslug;
        }
        */
        return $temp;
    }
}
