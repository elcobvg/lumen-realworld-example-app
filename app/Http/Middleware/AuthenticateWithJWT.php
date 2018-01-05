<?php

/*
 * Custom JWT authentication middleware since the original package does
 * not have a configurable option to change the authorization token name.
 *
 * The token name by default is set to 'bearer'.
 * The default middleware provided does not have any flexibility to
 * change the token name.
 *
 * This project api spec requires us to use the token name 'token'.
 */

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AuthenticateWithJWT extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param bool $optional
     * @return mixed
     */
    public function handle($request, Closure $next, $optional = null)
    {
        $this->auth->setRequest($request);

        try {
            if (! $user = $this->auth->parseToken('token')->authenticate()) {
                abort(401, 'JWT error: User not found');
            }
        } catch (TokenExpiredException $e) {
            abort(401, 'JWT error: Token has expired');
        } catch (TokenInvalidException $e) {
            abort(401, 'JWT error: Token is invalid');
        } catch (JWTException $e) {
            if ($optional === null) {
                abort(401);
            }
        }
        
        return $next($request);
    }
}
