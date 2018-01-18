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

$router->get('/', function () {
    return response(file_get_contents(__DIR__ . '/../readme.md'))
            ->header('Content-Type', 'text/plain');
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
    $router->group(['prefix' => 'profiles/{username}'], function ($router) {
        
        $router->get('/', 'ProfileController@show');
        $router->post('follow', 'ProfileController@follow');
        $router->delete('follow', 'ProfileController@unFollow');
    });

    /**
     * Articles
     */
    $router->get('articles', 'ArticleController@index');
    $router->post('articles', 'ArticleController@store');
    $router->get('articles/feed', 'ArticleController@feed');

    $router->group(['prefix' => 'articles/{slug:[a-z-]+}'], function ($router) {

        $router->get('/', 'ArticleController@show');
        $router->put('/', 'ArticleController@update');
        $router->delete('/', 'ArticleController@destroy');

        /**
         * Comments
         */
        $router->post('comments', 'CommentController@store');
        $router->get('comments', 'CommentController@index');
        $router->delete('comments/{id:[a-z0-9]+}', 'CommentController@destroy');

        /**
         * Favorites
         */
        $router->post('favorite', 'ArticleController@addFavorite');
        $router->delete('favorite', 'ArticleController@unFavorite');
    });

    /**
     * Tags
     */
    $router->get('tags', 'ArticleController@tags');
});
