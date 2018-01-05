<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesAuthenticationRequests
{
    /**
     * Validate login request input
     *
     * @param  Request $request
     * @throws \Illuminate\Auth\Access\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'user.email' => 'required|email|max:255',
            'user.password' => 'required',
        ]);
    }

    /**
     * Validate register request input
     *
     * @param  Request $request
     * @throws \Illuminate\Auth\Access\ValidationException
     */
    protected function validateRegister(Request $request)
    {
        $this->validate($request, [
            'user.username' => 'required|max:50|alpha_num|unique:users,username',
            'user.email' => 'required|email|max:255|unique:users,email',
            'user.password' => 'required|min:8',
        ]);
    }
}
