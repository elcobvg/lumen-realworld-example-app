<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserResource(Auth::user());
    }

    /**
     * Update the authenticated user and return the user if successful.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->has('user')) {
            $user->update($request->get('user'));
        }

        return new UserResource($user);
    }
}
