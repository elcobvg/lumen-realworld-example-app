<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    /**
     * CommentController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
        $this->middleware('auth:optional', ['only' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function index(string $slug)
    {
        $article = Article::where('slug', $slug)->first();
        return CommentResource::collection($article->comments);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
