<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Tag;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use App\RealWorld\Paginate\Paginator;
use App\Http\Resources\ArticleResource;
use App\RealWorld\Filters\ArticleFilter;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\Concerns\GetsResources;
use App\Http\Validators\ValidatesArticleRequests;

class ArticleController extends Controller
{
    use GetsResources, ValidatesArticleRequests;

    /**
     * ArticleController constructor.
     *
     * @param ArticleFilter $filter
     */
    public function __construct()
    {
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
    public function index(ArticleFilter $filter)
    {
        $articles = $this->paginate(Article::filter($filter));
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
        $this->validateNew($request);

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
        if (! $article = $this->getArticleBySlug($slug)) {
            abort(404);
        }
        return new ArticleResource($article);
    }

    /**
     * Update the article given by its slug and return the article if successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $slug)
    {
        $this->validateUpdate($request);

        if ($request->has('article')) {
            $article = $this->getArticleBySlug($slug);
            if ($request->user()->can('update-article', $article)) {
                $article->update($request->get('article'));
            }
        }
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $slug)
    {
        if (! $article = $this->getArticleBySlug($slug)) {
            abort(404);
        }

        if ($request->user()->can('delete-article', $article)) {
            $article->delete();
        }
        return $this->respondSuccess();
    }

    /**
     * Get all the articles of users that are followed by the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function feed()
    {
        $articles = $this->paginate(Auth::user()->feed());
        return ArticleResource::collection($articles);
    }

    /**
     * Favorite the article given by its slug and return the article if successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFavorite(Request $request, string $slug)
    {
        $article = $this->getArticleBySlug($slug);
        if ($request->user()->can('favorite-article', $article)) {
            $request->user()->favorite($article);
        }

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
        $article->save();
        
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

    /**
     * Paginate and filter the article collection
     *
     * @param  Collection $collection
     * @return Collection
     */
    protected function paginate(Collection $collection)
    {
        $paginator = new Paginator($collection);
        return $paginator->get();
    }
}
