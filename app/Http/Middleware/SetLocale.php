<?php

namespace App\Http\Middleware;

use App\Helpers\Support\LocaleResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale'));
        $locale = LocaleResolver::resolveTranslationLocale($locale);

        if (array_key_exists($locale, config('app.supported_locales'))) {
            app()->setLocale($locale);
            LocaleResolver::flushResolvedLocale();
        }

        return $next($request);
    }
}
