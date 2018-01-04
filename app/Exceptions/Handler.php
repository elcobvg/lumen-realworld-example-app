<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        /*
         * We add a custom exception renderer here since this will be an api only backend.
         * So we need to convert every exception to a json response.
         */

        if ($request->ajax() || $request->wantsJson()) {
            return $this->getJsonResponse($e);
        }

        return parent::render($request, $e);
    }

    /**
     * Get the json response for the exception.
     *
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponse(Exception $exception)
    {
        $debugEnabled = config('app.debug');

        /*
         * Handle validation errors thrown using ValidationException.
         */
        if ($exception instanceof ValidationException) {
            $validationErrors = $exception->validator->errors()->getMessages();

            /*
             * Laravel validation error format example
             * "attribute" => [
             *      "The attribute failed validation."
             * ]
             *
             * What we need as per the api spec
             * "attribute" => [
             *      "failed validation."
             * ]
             */
            $validationErrors = array_map(function ($error) {
                return array_map(function ($message) {
                    return remove_words($message, 2);
                }, $error);
            }, $validationErrors);

            return response()->json(['errors' => $validationErrors], 422);
        }

        $statusCode = 500;

        if (! isset($message) && ! ($message = $exception->getMessage())) {
            $message = sprintf('%d %s', $statusCode, Response::$statusTexts[$statusCode]);
        }

        $errors = [
            'message' => $message,
            'status_code' => $statusCode,
        ];

        if ($debugEnabled) {
            $errors['exception'] = get_class($exception);
            $errors['trace'] = explode("\n", $exception->getTraceAsString());
        }

        return response()->json(['errors' => $errors], $statusCode);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
