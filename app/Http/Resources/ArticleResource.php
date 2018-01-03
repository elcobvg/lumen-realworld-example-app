<?php

namespace App\Http\Resources;

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
            'favorited'         => !! $this->favorited,
            'favoritesCount'    => $this->favorited_by ? sizeof($this->favorited_by) : 0,
            'author' => [
                'username'  => $this->author->username,
                'bio'       => $this->author->bio,
                'image'     => $this->author->image,
                'following' => !! $this->author->following,
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
        return [
            'articles'      => $collection,
            'articlesCount' => $collection->count()
        ];
    }
}
