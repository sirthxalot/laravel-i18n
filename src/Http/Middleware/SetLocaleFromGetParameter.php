<?php

namespace Sirthxalot\Laravel\I18n\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Sirthxalot\Laravel\I18n\Translation;

class SetLocaleFromGetParameter
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $i18n = App::make(Translation::class);

        if ($request->has('locale') && $i18n->languageExists($request->get('locale'))) {
            App::setLocale($request->get('locale'));
        }

        return $next($request);
    }
}
