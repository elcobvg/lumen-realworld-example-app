<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\RealWorld\Filters\ArticleFilter;

class ArticleController extends Controller
{
    /** \App\RealWorld\Filters\ArticleFilter
     *
     * @var null
     */
    protected $filter;

    /**
     * ArticleController constructor.
     *
     * @param ArticleTransformer $transformer
     */
    public function __construct(ArticleFilter $filter)
    {
        $this->filter = $filter;

        // $this->middleware('auth.api')->except(['index', 'show']);
        // $this->middleware('auth.api:optional')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = $this->filter->apply(Article::all());
        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)->first();
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get all the articles of users that are followed by the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function feed()
    {
        return $this->respond(Article::all());
    }


    /**
     * Favorite the article given by its slug and return the article if successful.
     *
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFavorite(Article $article)
    {
    }

    /**
     * Unfavorite the article given by its slug and return the article if successful.
     *
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function unFavorite(Article $article)
    {
    }

    /**
     * Get all the tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tags()
    {
        $tags_raw = Article::all()->pluck('tags');
        $names = $tags_raw->flatMap(function ($values) {
            return $values->pluck('name');
        });
        $tags = $names->unique()->sort()->values()->all();
        return $this->respond(['tags' => $tags]);
    }
}
