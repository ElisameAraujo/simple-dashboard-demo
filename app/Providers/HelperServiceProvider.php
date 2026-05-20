<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $helpers = config('helpers.global', []);

        foreach ($helpers as $alias => $helper) {
            if (class_exists($helper)) {
                $alias = is_string($alias) ? $alias : class_basename($helper);

                if (!class_exists($alias, false)) {
                    class_alias($helper, $alias);
                }
            }
        }
    }
}
