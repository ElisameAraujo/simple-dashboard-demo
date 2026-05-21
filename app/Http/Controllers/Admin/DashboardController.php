<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\HelperDemoCatalog;

class DashboardController extends Controller
{
    public function index()
    {
        $helperDocs = HelperDemoCatalog::all();

        $summaryItems = [
            [
                'label' => __('pages/dashboard.summary.stack.label'),
                'value' => 'Laravel 13',
                'description' => __('pages/dashboard.summary.stack.description'),
                'icon' => 'fa-brands fa-laravel',
                'tone' => 'primary',
            ],
            [
                'label' => __('pages/dashboard.summary.helpers.label'),
                'value' => count($helperDocs),
                'description' => __('pages/dashboard.summary.helpers.description'),
                'icon' => 'fa-solid fa-toolbox',
                'tone' => 'secondary',
            ],
            [
                'label' => __('pages/dashboard.summary.locales.label'),
                'value' => '2',
                'description' => __('pages/dashboard.summary.locales.description'),
                'icon' => 'fa-solid fa-language',
                'tone' => 'accent',
            ],
            [
                'label' => __('pages/dashboard.summary.pages.label'),
                'value' => '5',
                'description' => __('pages/dashboard.summary.pages.description'),
                'icon' => 'fa-solid fa-layer-group',
                'tone' => 'info',
            ],
        ];

        $demoPages = [
            [
                'title' => __('pages/dashboard.demo_pages.profile.title'),
                'description' => __('pages/dashboard.demo_pages.profile.description'),
                'route' => 'account.my-profile',
                'icon' => 'fa-solid fa-user',
                'status' => __('pages/dashboard.status.available'),
            ],
            [
                'title' => __('pages/dashboard.demo_pages.notifications.title'),
                'description' => __('pages/dashboard.demo_pages.notifications.description'),
                'route' => 'account.notifications',
                'icon' => 'fa-regular fa-bell',
                'status' => __('pages/dashboard.status.available'),
            ],
            [
                'title' => __('pages/dashboard.demo_pages.security.title'),
                'description' => __('pages/dashboard.demo_pages.security.description'),
                'route' => 'account.security',
                'icon' => 'fa-solid fa-fingerprint',
                'status' => __('pages/dashboard.status.available'),
            ],
            [
                'title' => __('pages/dashboard.demo_pages.helpers.title'),
                'description' => __('pages/dashboard.demo_pages.helpers.description'),
                'route' => 'helpers.index',
                'icon' => 'fa-solid fa-toolbox',
                'status' => __('pages/dashboard.status.available'),
            ],
        ];

        $usefulLinks = [
            [
                'label' => __('pages/dashboard.useful_links.readme.label'),
                'description' => __('pages/dashboard.useful_links.readme.description'),
                'url' => $this->readmeUrl(),
                'icon' => 'fa-brands fa-readme',
            ],
            [
                'label' => __('pages/dashboard.useful_links.repository.label'),
                'description' => __('pages/dashboard.useful_links.repository.description'),
                'url' => 'https://github.com/ElisameAraujo/simple-dashboard',
                'icon' => 'fa-brands fa-github',
            ],
        ];

        $nextSteps = __('pages/dashboard.next_steps');

        return view('admin.dashboard.index', compact(
            'summaryItems',
            'demoPages',
            'helperDocs',
            'usefulLinks',
            'nextSteps'
        ));
    }

    private function readmeUrl(): string
    {
        return app()->getLocale() === 'pt_BR'
            ? 'https://github.com/ElisameAraujo/simple-dashboard/blob/main/README.pt-br.md'
            : 'https://github.com/ElisameAraujo/simple-dashboard';
    }
}
