<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public const ACCESS_KEY_SESSION = 'admin.access_key';

    public const ROUTE_PARAMETER = 'admin_key';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            return redirect()->route('wiki')->with('status', 'У вас нет прав для доступа к панели администратора.');
        }

        $sessionKey = (string) $request->session()->get(self::ACCESS_KEY_SESSION);
        $routeKey = (string) $request->route(self::ROUTE_PARAMETER);

        if ($sessionKey === '' || ! hash_equals($sessionKey, $routeKey)) {
            abort(404);
        }

        URL::defaults([self::ROUTE_PARAMETER => $sessionKey]);

        return $next($request);
    }
}
