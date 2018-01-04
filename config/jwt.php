<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Secret
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this, as it will be used to sign your tokens.
    | A helper command is provided for this: `php artisan jwt:generate`
    |
    */

    'secret' => env('JWT_SECRET', 'BvjpTAv86dMLdZdrGRSW6GFY9kbJXI3R'),

    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 1 hour
    |
    */

    'ttl' => 60 * 24 * 60, // 60 Days

    /*
    |--------------------------------------------------------------------------
    | Refresh time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token can be refreshed
    | within. I.E. The user can refresh their token within a 2 week window of
    | the original token being created until they must re-authenticate.
    | Defaults to 2 weeks
    |
    */

    'refresh_ttl' => 20160,

    /*
    |--------------------------------------------------------------------------
    | JWT hashing algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to sign the token.
    |
    | See here: https://github.com/namshi/jose/tree/2.2.0/src/Namshi/JOSE/Signer
    | for possible values
    |
    */

    'algo' => 'HS256',

    /*
    |--------------------------------------------------------------------------
    | User Model namespace
    |--------------------------------------------------------------------------
    |
    | Specify the full namespace to your User model.
    | e.g. 'Acme\Entities\User'
    |
    */

    'user' => 'App\Models\User',

    /*
    |--------------------------------------------------------------------------
    | User identifier
    |--------------------------------------------------------------------------
    |
    | Specify a unique property of the user that will be added as the 'sub'
    | claim of the token payload.
    |
    */

    'identifier' => 'id',

];
