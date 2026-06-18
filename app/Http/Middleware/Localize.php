<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\App;
use Closure;

class Localize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = request()->header('Language');

        App::setLocale($language);

        $routeName = $request->route() ? $request->route()->getName() : null;
        $allowedRoutes = [
            'mobile.signin',
            'mobile.signin.request',
            'mobile.password.reset',
            'mobile.password.reset.request',
            'mobile.password.reset.verify',
            'mobile.switch.language',
            'mobile.signup',
            'mobile.signup.request',
            'mobile.signup.verify',
        ];
        if ($routeName && strpos($routeName, 'mobile.') === 0 && !in_array($routeName, $allowedRoutes)) {
            if (!authUser('api')) {
                return app(\App\Http\Controllers\MobileController::class)->signin($request);
            }
        }

        return $next($request);
    }
}
