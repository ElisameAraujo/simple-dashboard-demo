<?php

namespace App\Http\Controllers;

use App\Helpers\Support\LocaleResolver;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        $locale = LocaleResolver::resolveTranslationLocale($locale);

        abort_unless(array_key_exists($locale, config('app.supported_locales')), 404);

        session()->put('locale', $locale);
        app()->setLocale($locale);
        LocaleResolver::flushResolvedLocale();

        return back();
    }
}
