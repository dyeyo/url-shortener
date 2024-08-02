<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetSecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Header para evitar la detección de tipos MIME incorrectos
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        //  Header para evita que el contenido sea cargado en un frame
        $response->headers->set('X-Frame-Options', 'DENY');

        // Header para  habilitar la protección XSS en navegadores
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self';");

        return $response;
    }
}
