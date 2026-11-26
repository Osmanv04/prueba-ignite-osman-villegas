<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSsoForProtectedTitle
{
    public function handle(Request $request, Closure $next): Response
    {
        /*
         * TODO: completa este middleware siguiendo el contrato del README.
         *
         * Pistas de Laravel:
         * - El parámetro {title} ya fue resuelto por route model binding.
         * - Puedes obtenerlo con $request->route('title').
         * - Sanctum expone el usuario con $request->user('sanctum').
         *
         * No modifiques el controlador, las rutas ni los modelos.
         */
        $title = $request->route('title');
        $user = $request->user('sanctum');
        if (!$title) {
            return abort(404);
        }

        if (!$title->requires_sso) {
            return $next($request);
        }

        if ($user) {
            return $next($request);
        }

        if ($title->requires_sso && !$request->user('sanctum')) {
            return response()->json([
                'code' => 'SSO_REQUIRED',
                'message' => 'Authentication is required to access this title.',
            ], 401);
        }

        return $next($request);
    }
}
