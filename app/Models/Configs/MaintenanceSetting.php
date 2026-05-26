<?php

namespace App\Models\Configs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MaintenanceSetting extends Model
{
    use HasFactory;

    public const DEFAULT_ONLINE_ALERT_DURATION_SECONDS = 30;

    protected const CACHE_KEY = 'configs.maintenance_settings';

    protected $fillable = [
        'maintenance_enabled',
        'maintenance_message',
        'show_header_shortcut',
        'show_online_alert',
        'online_alert_duration_seconds',
        'maintenance_disabled_at',
    ];

    protected function casts(): array
    {
        return [
            'maintenance_enabled' => 'boolean',
            'show_header_shortcut' => 'boolean',
            'show_online_alert' => 'boolean',
            'online_alert_duration_seconds' => 'integer',
            'maintenance_disabled_at' => 'datetime',
        ];
    }

    public static function current(): self
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            fn () => self::query()->first() ?? self::query()->create([
                'maintenance_enabled' => false,
                'show_header_shortcut' => false,
                'show_online_alert' => true,
                'online_alert_duration_seconds' => self::DEFAULT_ONLINE_ALERT_DURATION_SECONDS,
            ])
        );
    }

    public static function refreshCurrent(): self
    {
        Cache::forget(self::CACHE_KEY);

        return self::current();
    }

    public function customMaintenanceMessage(): ?string
    {
        return filled($this->maintenance_message) ? $this->maintenance_message : null;
    }

    public function shouldShowOnlineAlert(): bool
    {
        if ($this->maintenance_enabled || ! $this->show_online_alert) {
            return false;
        }

        if ($this->online_alert_duration_seconds === 0) {
            return true;
        }

        return $this->maintenance_disabled_at
            && $this->maintenance_disabled_at->gt(now()->subSeconds($this->online_alert_duration_seconds));
    }

    public function shouldPollOnlineAlert(): bool
    {
        return $this->shouldShowOnlineAlert()
            && $this->online_alert_duration_seconds > 0;
    }
}
