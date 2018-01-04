<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\Resource;

class ArticleResource extends Resource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'article';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'slug'              => $this->slug,
            'title'             => $this->title,
            'description'       => $this->description,
            'body'              => $this->body,
            'tagList'           => $this->tags->sortBy('name')->pluck('name'),
            'createdAt'         => $this->created_at->toAtomString(),
            'updatedAt'         => $this->updated_at->toAtomString(),
            'favorited'         => !! $this->favorited, // TODO: check if favorited by current iser
            'favoritesCount'    => $this->favoritesCount,
            'author' => [
                'username'  => $this->author->username,
                'bio'       => $this->author->bio,
                'image'     => $this->author->image,
                'following' => !! $this->author->following, // TODO: check if followed by current iser
            ]
        ];
    }

    /**
     * Create new resource collection.
     *
     * @param  mixed  $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        $collection = parent::collection($resource)->collection;
        $wrap = Str::plural(self::$wrap);
        return [
            $wrap           => $collection,
            $wrap . 'Count' => $collection->count()
        ];
    }
}
