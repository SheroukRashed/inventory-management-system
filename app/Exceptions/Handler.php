<?php

namespace App\Exceptions;

use App\Traits\APIResponseHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use APIResponseHandler;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        $response = $this->handleException($request, $exception);
        return $response;
    }

    /**
     * @param mixed $request
     * @param Throwable $exception
     * 
     * @return [type]
     */
    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse('The user is not authorized', 401);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse(
                'Forbidden. The user does not have the permissions to perform this action.', 
                403
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('The specified URL cannot be found', 404);
        }

        if ($exception instanceof MethodNotAllowedException) {
            return $this->errorResponse('The specified method for the request is invalid', 405);
        }

        if( $exception instanceOf ValidationException)
        {
            return $this->errorResponse($exception->getMessage(), 422);
        }
    
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);            
        }
        return $this->errorResponse('Unexpected Exception. Try later', 500);
    }
}
