<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Tag;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Helpers\GetsResources;
use App\Http\Resources\ArticleResource;
use App\RealWorld\Filters\ArticleFilter;

class ArticleController extends Controller
{
    use GetsResources;

    /** \App\RealWorld\Filters\ArticleFilter
     *
     * @var null
     */
    protected $filter;

    /**
     * ArticleController constructor.
     *
     * @param ArticleFilter $filter
     */
    public function __construct(ArticleFilter $filter)
    {
        $this->filter = $filter;

        $this->middleware('auth', ['except' => [
            'index',
            'show',
            'tags'
        ]]);
        $this->middleware('auth:optional', ['only' => [
            'index',
            'show'
        ]]);
    }

    /**
     * Get all the articles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = $this->filter->apply(Article::all());
        return ArticleResource::collection($articles);
    }

    /**
     * Create a new article and return the article if successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $article = Article::create([
            'title' => $request->input('article.title'),
            'description' => $request->input('article.description'),
            'body' => $request->input('article.body'),
        ]);

        Auth::user()->articles()->save($article);

        $inputTags = $request->input('article.tagList');

        if ($inputTags && ! empty($inputTags)) {
            foreach ($inputTags as $name) {
                $article->tags()->attach(new Tag(['name' => $name]));
            }
        }

        return new ArticleResource($article);
    }

    /**
     * Get the article given by its slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        return new ArticleResource($this->getArticleBySlug($slug));
    }

    /**
     * Update the article given by its slug and return the article if successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        if ($request->has('article')) {
            $article = $this->getArticleBySlug($slug);
            $article->update($request->get('article'));
        }
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug)
    {
        if ($article = $this->getArticleBySlug($slug)) {
            $article->delete();
            return $this->respondSuccess();
        }
        return $this->respondNotFound();
    }

    /**
     * Get all the articles of users that are followed by the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function feed()
    {
        $following_ids = Auth::user()->following->pluck('id');
        $articles = Article::whereIn('author_id', $following_ids)->get();
        return ArticleResource::collection($articles);
    }

    /**
     * Favorite the article given by its slug and return the article if successful.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFavorite(string $slug)
    {
        $article = $this->getArticleBySlug($slug);
        Auth::user()->favorite($article);

        return new ArticleResource($article);
    }

    /**
     * Unfavorite the article given by its slug and return the article if successful.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function unFavorite(string $slug)
    {
        $article = $this->getArticleBySlug($slug);
        Auth::user()->unFavorite($article);
        
        return new ArticleResource($article);
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
