<?php

namespace App\Exceptions;

use Exception;
use Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use App\Http\Controllers\API\APIController;

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Check exception authentication and guard authentication is api
        if ($exception instanceof AuthenticationException && in_array('api', $exception->guards())) {
            $data_format = [
                'version' => APIController::VERSION_API,
                'status' => [
                    'code' => APIController::CODE_EXCEPTION_FROM_SERVER,
                    'message' => [trans('auth.unauthenticated')],
                    'api' => Request::path()
                ],
                'result' => (object)[]
            ];
            return response()->json($data_format, 401);
        }

        return parent::render($request, $exception);
    }
}
