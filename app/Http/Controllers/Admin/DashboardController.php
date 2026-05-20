<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $helperDocs = $this->helperDocs();

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
                'value' => '4',
                'description' => __('pages/dashboard.summary.pages.description'),
                'icon' => 'fa-solid fa-layer-group',
                'tone' => 'info',
            ],
        ];

        $demoPages = [
            [
                'title' => __('pages/dashboard.demo_pages.profile.title'),
                'description' => __('pages/dashboard.demo_pages.profile.description'),
                'route' => 'admin.account.my-profile',
                'icon' => 'fa-solid fa-user',
                'status' => __('pages/dashboard.status.available'),
            ],
            [
                'title' => __('pages/dashboard.demo_pages.notifications.title'),
                'description' => __('pages/dashboard.demo_pages.notifications.description'),
                'route' => 'admin.account.notifications',
                'icon' => 'fa-regular fa-bell',
                'status' => __('pages/dashboard.status.available'),
            ],
            [
                'title' => __('pages/dashboard.demo_pages.security.title'),
                'description' => __('pages/dashboard.demo_pages.security.description'),
                'route' => 'admin.account.security',
                'icon' => 'fa-solid fa-fingerprint',
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

    private function helperDocs(): array
    {
        $documentationLocale = app()->getLocale() === 'pt_BR' ? 'pt-BR' : 'en';

        return collect([
            ['key' => 'date-helper', 'file' => 'DateHelper.md', 'icon' => 'fa-regular fa-calendar-days'],
            ['key' => 'disk-helper', 'file' => 'DiskHelper.md', 'icon' => 'fa-regular fa-hard-drive'],
            ['key' => 'media-helper', 'file' => 'MediaHelper.md', 'icon' => 'fa-regular fa-image'],
            ['key' => 'notification-helper', 'file' => 'NotificationHelper.md', 'icon' => 'fa-regular fa-bell'],
            ['key' => 'number-helper', 'file' => 'NumberHelper.md', 'icon' => 'fa-solid fa-arrow-down-1-9'],
            ['key' => 'routes-helper', 'file' => 'RoutesHelper.md', 'icon' => 'fa-solid fa-route'],
            ['key' => 'text-helper', 'file' => 'TextHelper.md', 'icon' => 'fa-solid fa-font'],
            ['key' => 'user-helper', 'file' => 'UserHelper.md', 'icon' => 'fa-regular fa-user'],
        ])->map(function (array $helper) use ($documentationLocale): array {
            return [
                'name' => __("docs/helpers/{$helper['key']}.name"),
                'description' => __("docs/helpers/{$helper['key']}.description"),
                'url' => "https://github.com/ElisameAraujo/simple-dashboard/blob/main/documentation/{$documentationLocale}/{$helper['file']}",
                'icon' => $helper['icon'],
            ];
        })->all();
    }

    private function readmeUrl(): string
    {
        return app()->getLocale() === 'pt_BR'
            ? 'https://github.com/ElisameAraujo/simple-dashboard/blob/main/README.pt-br.md'
            : 'https://github.com/ElisameAraujo/simple-dashboard';
    }
}
