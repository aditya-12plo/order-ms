<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Http\Response;
use App\Models\Log;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // return parent::render($request, $exception);

        $rendered   = parent::render($request, $exception);
        $url        = $request->path();
        $hostname   = gethostname();
		
		if ($exception instanceof ValidationException) {
			$message = json_decode($exception->getResponse()->content());
		}else{
            if($rendered->getStatusCode() == 404){
                $message    = "page not found";
            }else{
                $message    = $exception->getMessage();
            }
		}
		
        
        $level      = $rendered->getStatusCode();
        $channel    = $config['name'] ?? env('APP_ENV');
        $ip         = request()->server('REMOTE_ADDR');
        $user_agent = request()->server('HTTP_USER_AGENT');
        
        Log::create([
            'instance'      => $hostname,
            'channel'       => $channel,
            'message'       => $message,
            'level'         => $level,
            'ip'            => $ip,
            'user_agent'    => $user_agent,
            'url'           => $url,
            'context'       => $exception,
            'extra'         => $request

        ]);

        return response()
        ->json([
            'status'=>$level ,
            'datas' => null, 
            'errors' => [
                'message' => $message, 
            ]
            ])
        ->withHeaders([
            'Content-Type'          => 'application/json',
            ])
        ->setStatusCode($level);
        
    }
}
