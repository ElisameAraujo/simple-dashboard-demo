<?php

namespace Tests\Unit;

use App\Helpers\NotificationHelper;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationHelperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function test_guest_receives_safe_empty_notification_values(): void
    {
        $this->assertSame(0, NotificationHelper::allUnreadNotificationsCount());
        $this->assertSame(0, NotificationHelper::unreadNotificationsByTypeCount('NewMessageNotification', 'User'));
        $this->assertTrue(NotificationHelper::latestNotifications()->isEmpty());
        $this->assertTrue(NotificationHelper::allUnreadNotifications()->isEmpty());
        $this->assertTrue(NotificationHelper::unreadNotificationsByType('NewMessageNotification', 'User')->isEmpty());
    }

    public function test_latest_notifications_returns_read_and_unread_notifications_with_a_limit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        foreach (range(1, 12) as $index) {
            $this->createNotification(
                user: $user,
                type: 'App\\Notifications\\SystemNotification',
                readAt: $index % 2 === 0 ? now() : null,
                createdAt: now()->subMinutes(12 - $index),
                data: ['title' => "Notification {$index}"]
            );
        }

        $notifications = NotificationHelper::latestNotifications();

        $this->assertCount(10, $notifications);
        $this->assertSame('Notification 12', $notifications->first()->data['title']);
        $this->assertSame('Notification 3', $notifications->last()->data['title']);
    }

    public function test_all_unread_notifications_returns_every_unread_notification_by_default(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        foreach (range(1, 15) as $index) {
            $this->createNotification(
                user: $user,
                type: 'App\\Notifications\\SystemNotification',
                readAt: null,
                createdAt: now()->subMinutes(15 - $index),
                data: ['title' => "Unread {$index}"]
            );
        }

        $this->createNotification(
            user: $user,
            type: 'App\\Notifications\\SystemNotification',
            readAt: now(),
            createdAt: now(),
            data: ['title' => 'Read notification']
        );

        $notifications = NotificationHelper::allUnreadNotifications();

        $this->assertCount(15, $notifications);
        $this->assertSame(15, NotificationHelper::allUnreadNotificationsCount());
        $this->assertSame('Unread 15', $notifications->first()->data['title']);
    }

    public function test_all_unread_notifications_can_still_be_limited(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        foreach (range(1, 5) as $index) {
            $this->createNotification(
                user: $user,
                type: 'App\\Notifications\\SystemNotification',
                readAt: null,
                createdAt: now()->subMinutes(5 - $index),
                data: ['title' => "Unread {$index}"]
            );
        }

        $this->assertCount(2, NotificationHelper::allUnreadNotifications(2));
    }

    public function test_unread_notifications_by_type_filters_by_notification_class(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $targetType = 'App\\Notifications\\User\\NewMessageNotification';

        $this->createNotification($user, $targetType, null, now()->subMinutes(3), ['title' => 'Older target']);
        $this->createNotification($user, $targetType, null, now()->subMinute(), ['title' => 'Newer target']);
        $this->createNotification($user, $targetType, now(), now(), ['title' => 'Read target']);
        $this->createNotification($user, 'App\\Notifications\\SystemNotification', null, now(), ['title' => 'Other type']);

        $notifications = NotificationHelper::unreadNotificationsByType('NewMessageNotification', 'User');

        $this->assertCount(2, $notifications);
        $this->assertSame(2, NotificationHelper::unreadNotificationsByTypeCount('NewMessageNotification', 'User'));
        $this->assertSame('Newer target', $notifications->first()->data['title']);
    }

    public function test_unread_notifications_by_type_accepts_a_fully_qualified_class_name(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $type = 'App\\Notifications\\User\\NewMessageNotification';

        $this->createNotification($user, $type, null, now(), ['title' => 'Target']);

        $this->assertSame(1, NotificationHelper::unreadNotificationsByTypeCount($type));
    }

    private function createNotification(User $user, string $type, mixed $readAt, mixed $createdAt, array $data): void
    {
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => $type,
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->getKey(),
            'data' => json_encode($data),
            'read_at' => $readAt,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }
}
