<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Exception;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, $exception)
    {
        if ($request->isJson()) {
            if ($request->is('api/*','crop/*','account/*','cpmtype/*','cpmtype/*','pdm/*')) {
                if ($exception instanceof NotFoundHttpException) {
                    return response()->json(['message' => 'Not Found'], 404);
                }

                if ($exception instanceof TokenExpiredException) {
                    return response()->json(['message' => $exception->getMessage()], 401);
                }

                return response()->json(['message' => $exception->getMessage()], 500);
            } else {
                return response()->json(['message' => "Invalid Request"], 50);
            }
        } else {
            return parent::render($request, $exception);
        }
    }


}
