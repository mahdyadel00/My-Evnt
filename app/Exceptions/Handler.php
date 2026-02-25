<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Arr;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                try {
                    switch ($e->getStatusCode()) {
                        case 201:
                            $message = __('messages.created');
                            break;
                        case 204:
                            $message = __('messages.no_content');
                            break;
                        case 400:
                            $message = __('messages.bad_request');
                            break;
                        case 401:
                            $message = __('messages.unauthorized');
                            break;
                        case 403:
                            $message = __('messages.forbidden');
                            break;
                        case 409:
                            $message = __('messages.resource_not_found');
                            break;
                        case 405:
                            $message = __('messages.method_not_allowed');
                            break;
                        case 422:
                            $message = __('messages.unprocessable_entity');
                            break;
                        case 409:
                            $message = __('messages.conflict');
                            break;
                        case 500:
                            $message = __('messages.internal_server_error');
                            break;
                        default:
                            $message = __('messages.something_went_wrong');
                            break;
                    }

                    return config('app.debug') ? response()->json([
                        'message' => ($e->getMessage()) ? $e->getMessage() : $message,
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => collect($e->getTrace())->map(function ($trace) {
                            return Arr::except($trace, ['args']);
                        })->all(),
                    ], $e->getStatusCode()) : response()->json([
                        'message' => $this->isHttpException($e) ? (($e->getMessage()) ? $e->getMessage() : $message) : 'Whoops, looks like something went wrong',
                    ], $e->getStatusCode());
                } catch (\Throwable $th) {
                }
            }
        });
    }
}
