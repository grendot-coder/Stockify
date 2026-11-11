<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     * Mencegah halaman mentah 419 Page Expired saat demonstrasi atau saat berganti-ganti akun.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            // Regenerasi token CSRF baru di sesi pengguna
            $request->session()->regenerateToken();

            if ($request->expectsJson() || $request->isXmlHttpRequest()) {
                return response()->json([
                    'message' => 'Sesi formulir telah kedaluwarsa. Token CSRF baru telah dibuat.',
                    'csrf_token' => csrf_token(),
                ], 419);
            }

            $targetRoute = $request->is('customer*') ? 'customer.login' : 'login';

            return redirect()->route($targetRoute)->with('info', 'Sesi Anda telah diperbarui otomatis demi keamanan. Silakan coba masuk kembali.');
        }

        return parent::render($request, $e);
    }
}
