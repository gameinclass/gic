<?php

namespace App\Exceptions;

use Exception;
use App\Notifications\Exceptions;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{

    /**
     * Route notifications for the Slack channel.
     *
     * @var string
     */
    private $route = 'https://hooks.slack.com/services/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';

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
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            /*Notification::route('slack', $this->route)
                ->notify(new Exceptions($exception->getMessage()));*/
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
