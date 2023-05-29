<?php

namespace Sirthxalot\Laravel\I18n\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Sirthxalot\Laravel\I18n\Translation;

class SetLocaleFromSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $sessionKey = config('i18n.locale_sk');

        if (! Session::has($sessionKey)) {
            return $next($request);
        }

        $i18n = App::make(Translation::class);

        if (! $i18n->languageExists(Session::get($sessionKey))) {
            Log::warning(
                __(
                    'Failed to update locale (:locale) from session - language is missing.',
                    ['locale' => Session::get($sessionKey)]
                )
            );

            return $next($request);
        }

        App::setLocale(Session::get($sessionKey));

        return $next($request);
    }
}
