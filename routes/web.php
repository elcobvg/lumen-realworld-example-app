<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Generate random string
$router->get('appKey', function () {
    return str_random('32');
});

$router->group(['prefix' => 'api'], function ($router) {

    /**
     * Authentication
     */
    $router->post('users/login', 'AuthController@login');
    $router->post('users', 'AuthController@register');

    /**
     * Current user
     */
    $router->get('user', 'UserController@index');
    $router->put('user', 'UserController@update');

    /**
     * User profile
     */
    $router->get('profiles/{username}', 'ProfileController@show');
    $router->post('profiles/{username}/follow', 'ProfileController@follow');
    $router->delete('profiles/{username}/follow', 'ProfileController@unFollow');

    /**
     * Articles
     */
    $router->get('articles', 'ArticleController@index');
    $router->get('articles/feed', 'ArticleController@feed');
    $router->get('articles/{slug:[a-z-]+}', 'ArticleController@show');
    $router->post('articles', 'ArticleController@store');
    $router->put('articles/{slug:[a-z-]+}', 'ArticleController@update');
    $router->delete('articles/{slug:[a-z-]+}', 'ArticleController@destroy');

    /**
     * Comments
     */
    $router->post('articles/{slug:[a-z-]+}/comments', 'CommentController@store');
    $router->get('articles/{slug:[a-z-]+}/comments', 'CommentController@index');
    $router->delete('articles/{slug:[a-z-]+}/comments', 'CommentController@destroy');

    /**
     * Favorites
     */
    $router->post('articles/{slug:[a-z-]+}/favorite', 'ArticleController@addFavorite');
    $router->delete('articles/{slug:[a-z-]+}/favorite', 'ArticleController@unFavorite');

    /**
     * Tags
     */
    $router->get('tags', 'ArticleController@tags');
});
