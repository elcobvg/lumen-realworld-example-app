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
        return Article::where('slug', $slug)->first();
    }
}
