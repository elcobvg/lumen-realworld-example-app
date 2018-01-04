<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginUser;
use App\Http\Requests\RegisterUser;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    /**
     * Login user and return the user is successful.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'user.email' => 'required|email|max:255',
            'user.password' => 'required',
        ]);
        
        $credentials = $request->all()['user'];

        if (! Auth::once($credentials)) {
            return $this->respondFailedLogin();
        }

        return new UserResource(Auth::user());
    }

    /**
     * Register a new user and return the user if successful.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'user.username' => 'required|max:50|alpha_num|unique:users,username',
            'user.email' => 'required|email|max:255|unique:users,email',
            'user.password' => 'required|min:8',
        ]);

        $user = User::create([
            'username' => $request->input('user.username'),
            'email' => $request->input('user.email'),
            'password' => $request->input('user.password'),
        ]);

        return new UserResource($user);
    }
}
