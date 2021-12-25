<?php

namespace App\Exceptions;

use App\Http\Utils\ApiUtil;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    private function handleWebException($request, Throwable $exception)
    {

        if ($exception instanceof WebServiceException) {
            return redirect()->back()->withErrors($exception->getValidator())->withInput();
        }

        if ($exception instanceof WebServiceErroredException) {
            return redirect()->back()->with('error', $exception->getExplanation());
        }
        return parent::render($request, $exception);
    }

    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \App\Exceptions\ApiServiceException) {
            return $exception->getApiResponse();
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'Bad Request'], 404);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (ApiUtil::checkUrlIsApi($request)) {
            return $this->handleApiException($request, $exception);
        } else {
            return $this->handleWebException($request, $exception);
        }
    }
}
