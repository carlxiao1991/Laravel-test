<?php

namespace App\Exceptions;

use HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $error = $this->convertExceptionToResponse($exception);
        $data = [
            'code' => $error->getStatusCode(),
            'message' => $exception->getMessage(),
            'data' => [],
        ];
        if ($exception instanceof HttpException) {
            $data = [
                'code' => 500,
                'message' => $exception->getMessage(),
                'data' => [],
            ];
            if (!config('app.debug')) {
                $data['message'] = 'Internal Server Error';
            }
        }
        if (config('app.debug')) {
            $data['data'] = $exception->getTrace();
        }
        if($exception instanceof NotFoundHttpException){
            $data['message'] = 'Not Found';
        }
        return response()->json($data, $error->getStatusCode());
    }
}
