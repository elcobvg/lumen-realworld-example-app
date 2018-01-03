<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ArticleCollection(Article::all());
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        return Article::all();
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
    }
}
