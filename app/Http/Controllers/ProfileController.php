<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
use App\Http\Controllers\Helpers\GetsResources;

class ProfileController extends Controller
{
    use GetsResources;
    
    /**
     * ProfileController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
        $this->middleware('auth:optional', ['only' => 'show']);
    }

    /**
     * Get the profile of the user given by their username
     *
     * @param string $username
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        if (! $user = $this->getUserByName($username)) {
            abort(404);
        }
        return new ProfileResource($user);
    }

    /**
     * Follow the user given by their username and return the user if successful.
     *
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(string $username)
    {
        $user = $this->getUserByName($username);
        Auth::user()->follow($user);
        return new ProfileResource($user);
    }

    /**
     * Unfollow the user given by their username and return the user if successful.
     *
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function unFollow(string $username)
    {
        $user = $this->getUserByName($username);
        Auth::user()->unFollow($user);
        return new ProfileResource($user);
    }
}
