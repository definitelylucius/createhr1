<?php



use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            '2fa' => \App\Http\Middleware\EnsureTwoFactorIsVerified::class,
        ]);
        
        // Optional: Apply globally to web routes
        $middleware->web(append: [
            \App\Http\Middleware\EnsureTwoFactorIsVerified::class,
        ]);
   
 
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


   


    