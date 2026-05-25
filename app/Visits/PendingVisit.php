<?php

namespace App\Visits;

use App\Visits\Models\Visit;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PendingVisit
{
    private string $visitorType = 'ip';
    private string $visitorHash;
    private ?int $userId = null;
    private ?array $data = null;

    public function __construct(private readonly Model $visitable)
    {
        $this->withIp();
    }

    public function withIp(?string $ip = null): self
    {
        $this->visitorType = 'ip';
        $this->visitorHash = $this->hash($ip ?? request()->ip() ?? 'unknown');

        return $this;
    }

    public function withUuid(string $uuid): self
    {
        $this->visitorType = 'uuid';
        $this->visitorHash = $this->hash($uuid);

        return $this;
    }

    public function withSession(?string $sessionId = null): self
    {
        $this->visitorType = 'session';
        $this->visitorHash = $this->hash($sessionId ?? session()->getId());

        return $this;
    }

    public function withUser(?Model $user = null): self
    {
        $user ??= Auth::user();

        if ($user) {
            $this->userId = $user->getKey();
            $this->visitorType = 'user';
            $this->visitorHash = $this->hash($user::class . ':' . $user->getKey());
        }

        return $this;
    }

    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function hourlyInterval(): Visit
    {
        return $this->record('hourly', now()->format('Y-m-d-H'));
    }

    public function dailyInterval(): Visit
    {
        return $this->record('daily', now()->format('Y-m-d'));
    }

    public function weeklyInterval(): Visit
    {
        return $this->record('weekly', now()->format('o-\WW'));
    }

    public function monthlyInterval(): Visit
    {
        return $this->record('monthly', now()->format('Y-m'));
    }

    public function yearlyInterval(): Visit
    {
        return $this->record('yearly', now()->format('Y'));
    }

    public function customInterval(CarbonInterface $startsAt): Visit
    {
        return $this->record('custom', $startsAt->format('Y-m-d-H-i-s'));
    }

    private function record(string $interval, string $intervalKey): Visit
    {
        return Visit::firstOrCreate([
            'visitable_type' => $this->visitable->getMorphClass(),
            'visitable_id' => $this->visitable->getKey(),
            'visitor_type' => $this->visitorType,
            'visitor_hash' => $this->visitorHash,
            'interval' => $interval,
            'interval_key' => $intervalKey,
        ], [
            'user_id' => $this->userId,
            'data' => $this->data,
            'visited_at' => now(),
        ]);
    }

    private function hash(string $value): string
    {
        return hash_hmac('sha256', $value, config('app.key'));
    }
}
