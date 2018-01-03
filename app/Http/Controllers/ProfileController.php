<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Get the profile of the user given by their username
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Follow the user given by their username and return the user if successful.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(User $user)
    {
    }

    /**
     * Unfollow the user given by their username and return the user if successful.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function unFollow(User $user)
    {
    }
}
