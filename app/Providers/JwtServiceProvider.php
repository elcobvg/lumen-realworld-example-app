<?php

/*
 * Custom JWT authentication service provider since the original package does
 * not have a configurable option to change the authorization token name.
 *
 * The token name by default is set to 'bearer'.
 * This project api spec requires us to use the token name 'token'.
 */

namespace App\Providers;

use Tymon\JWTAuth\Http\Parser\AuthHeaders;
use Tymon\JWTAuth\Http\Parser\InputSource;
use Tymon\JWTAuth\Http\Parser\QueryString;
use Tymon\JWTAuth\Http\Parser\LumenRouteParams;
use Tymon\JWTAuth\Providers\AbstractServiceProvider;

class JwtServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->app->configure('jwt');

        $path = realpath(__DIR__.'/../../vendor/tymon/jwt-auth/config/config.php');
        $this->mergeConfigFrom($path, 'jwt');

        $this->app->routeMiddleware($this->middlewareAliases);

        $this->extendAuthGuard();

        $this->app['tymon.jwt.parser']->setChain([
            with(new AuthHeaders)->setHeaderPrefix('token'),
            new QueryString,
            new InputSource,
            new LumenRouteParams,
        ]);
    }
}
