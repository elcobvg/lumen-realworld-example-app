<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesArticleRequests
{
    /**
     * Validate new article request input
     *
     * @param  Request $request
     * @throws \Illuminate\Auth\Access\ValidationException
     */
    protected function validateNew(Request $request)
    {
        $this->validate($request, [
            'article.title'         => 'required|string|max:255',
            'article.description'   => 'required|string|max:255',
            'article.body'          => 'required|string',
            'article.tagList'       => 'sometimes|array',
        ]);
    }

    /**
     * Validate update article request input
     *
     * @param  Request $request
     * @throws \Illuminate\Auth\Access\ValidationException
     */
    protected function validateUpdate(Request $request)
    {
        $this->validate($request, [
            'article.title'         => 'sometimes|string|max:255',
            'article.description'   => 'sometimes|string|max:255',
            'article.body'          => 'sometimes|string',
        ]);
    }
}
