<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\RealWorld\Transformers\TagTransformer;

class TagController extends Controller
{
    /**
     * TagController constructor.
     *
     * @param TagTransformer $transformer
     */
    public function __construct(TagTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Get all the tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tags_raw = Article::all()->pluck('tags');
        $names = $tags_raw->flatMap(function ($values) {
            return $values->pluck('name');
        });
        $tags = $names->unique()->sort()->values()->all();

        return $this->respondWithTransformer($tags);
    }
}
