<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

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
        if ($e instanceof HttpException) {
            $message = $e->getMessage() ?: Response::$statusTexts[$e->getStatusCode()];

            return response()->json((
                ['errors' => [
                    'status' => $e->getStatusCode(),
                    'message' => $message,
                ]
                ]), $e->getStatusCode());
        }

        if ($e instanceof ValidationException) {
            $formattedErrors = [];
            foreach ($e->validator->errors()->getMessages() as $key => $messages) {
                $key = preg_replace('/[a-z]+\./', '', $key);
                $formattedErrors[$key] = array_map(function ($msg) {
                    return preg_replace('/The [a-zA-Z ]+\.([a-z]+)/', '', $msg);
                }, $messages);
            }
            return response()->json(['errors' => $formattedErrors], 422);
        }

        if ($e instanceof Exception) {
            return response()->json(['errors' => [class_basename($e) => $e->getMessage()]], 500);
        }
    }
}
