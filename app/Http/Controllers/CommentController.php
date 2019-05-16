<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use App\Http\Controllers\Concerns\GetsArticles;

class CommentController extends Controller
{
    use GetsArticles;

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
        $article = $this->getArticleBySlug($slug);
        return CommentResource::collection($article->comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $slug)
    {
        $this->validate($request, [
            'comment.body' => 'required|string',
        ]);

        $article = $this->getArticleBySlug($slug);

        $article->comments()->create([
            'body' => $request->input('comment.body'),
            'author_id' => Auth::user()->id,
        ]);
        $comment = $article->comments->pop();

        return (new CommentResource($comment))->response()->header('Status', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $slug, string $id)
    {
        $article = $this->getArticleBySlug($slug);
        $comment = $article->comments()->firstWhere('id', $id);

        if ($request->user()->cannot('delete-comment', $comment)) {
            abort(401);
        }

        if ($comment->delete()) {
            return $this->respondSuccess();
        }
    }
}
