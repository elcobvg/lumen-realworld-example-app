<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesUserRequests
{
    /**
     * Validate update user request input
     *
     * @param  Request $request
     * @throws \Illuminate\Auth\Access\ValidationException
     */
    protected function validateUpdate(Request $request)
    {
        if ($request->user()->email === $request->input('user.email')) {
            $email_rule = 'email';
        } else {
            $email_rule = 'sometimes|email|max:255|unique:users,email';
        }
        
        $this->validate($request, [
            'user.username' => 'sometimes|max:50|alpha_num|unique:username',
            'user.email' => $email_rule,
            'user.password' => 'sometimes|min:8',
            'user.bio' => 'sometimes|nullable|max:255',
            'user.image' => 'sometimes|nullable|url',
        ]);
    }
}
