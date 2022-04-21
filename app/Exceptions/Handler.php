<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Maknz\Slack\Facades\Slack;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $e)
    {
        error_log('-- exception :( --');
        $m = $e->getMessage();
        $f = $e->getFile();
        $l = $e->getLine();
        error_log("------------->");
        error_log("$m $f:$l");
        error_log($e->getTraceAsString());
        error_log('++++++++++++++++++++++++++++++++++++++++++++');

        $request = request();
        error_log($request);

        if ((!($e instanceof NotFoundHttpException))
         && (!($e instanceof AuthenticationException))
         && (!($e instanceof TokenMismatchException))
         && (!($e instanceof MethodNotAllowedHttpException))
         //&& (!(config('app.debug')))
        ) {
            try {

                $slack_channel = '#medic_errors_v2';
                if (!App::environment('portal')) $slack_channel = '#testing_tests';

                $slack = Slack::to($slack_channel);
                $fields = [
                    [
                        "title" => '[NEW] User',
                        "value" => isset(Auth::user()->email) ? Auth::user()->email : "NO USER",
                        "short" => true,
                    ],
                    [
                        "title" => '[NEW] Canvas id',
                        "value" => isset($_POST['canvas_id']) ? $_POST['canvas_id'] : "NO CANVAS ID",
                        "short" => true,
                    ],
                    [
                        "title" => 'File',
                        "value" => $f,
                        "short" => true,
                    ],
                    [
                        "title" => 'Line',
                        "value" => $l,
                        "short" => true,
                    ],
                    [
                        "title" => 'Message',
                        "value" => $m,
                        "short" => false,
                    ],
                    [
                        "title" => '[NEW] Request',
                        "value" => $request . "",
                        "short" => false,
                    ],
                    [
                        "title" => '[NEW] Trace',
                        "value" => substr($e->getTraceAsString(), 0, 800),
                        "short" => false,
                    ],
                ];
                $slack->attach([
                    "color" => 'default',
                    "fields" => $fields,
                ]);

                $slack->withIcon('broken_heart')->send(":broken_heart: Error in " . App::environment());

            } catch (Exception $e_slack) {
                error_log('error sending medic to slack');
                error_log($e_slack->getTraceAsString());
                error_log('----------------------');
            }
        }

        parent::report($e);
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
        if ($exception instanceof MethodNotAllowedHttpException)
            return response()->view("token_expired");

        return parent::render($request, $exception);
    }
}
