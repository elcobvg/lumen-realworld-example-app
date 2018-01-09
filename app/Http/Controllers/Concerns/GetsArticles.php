<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Article;

trait GetsArticles
{
    /**
     * Retrieve article by its slug
     * @param  string $slug
     * @return \App\Models\Article
     */
    protected function getArticleBySlug(string $slug)
    {
        if (! $article = Article::where('slug', $slug)->first()) {
            abort(404);
        }
        return $article;
    }
}
