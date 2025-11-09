<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        // Catch post-too-large exception thrown by framework's ValidatePostSize middleware
        if ($e instanceof PostTooLargeException) {
            $message = 'Ukuran file terlalu besar. Maksimum 2MB.';

            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => $message,
                    'errors' => [
                        'image' => [$message]
                    ]
                ], 422);
            }

            // Try to redirect back with session errors. This may fail if session
            // middleware hasn't run (POST-too-large can occur early). Wrap in try/catch
            // and fallback to a referer redirect with query params.
            try {
                return redirect()->back()->withInput()->withErrors(['image' => $message]);
            } catch (\Throwable $ex) {
                $referer = $request->headers->get('referer') ?: route('admin.trainers.index');
                $sep = strpos($referer, '?') === false ? '?' : '&';
                $url = $referer.$sep.'upload_error=1&upload_error_message='.rawurlencode($message);
                return new RedirectResponse($url);
            }
        }

        return parent::render($request, $e);
    }
}
