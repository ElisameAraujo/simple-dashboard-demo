<?php

namespace Tests\Unit;

use App\Helpers\UserHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserHelperTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_receives_safe_user_values(): void
    {
        $this->assertFalse(UserHelper::userLogged());
        $this->assertSame('Guest', UserHelper::info('name', 'Guest'));
        $this->assertFalse(UserHelper::userIsActive());
        $this->assertNull(UserHelper::userId());
        $this->assertNull(UserHelper::username());
        $this->assertNull(UserHelper::userEmail());
        $this->assertNull(UserHelper::emailDomain());
        $this->assertNull(UserHelper::userAvatar());
        $this->assertNull(UserHelper::userAvatarPath());
        $this->assertSame(['initials' => '?', 'color' => '#3498db'], UserHelper::userAvatarFallback());
        $this->assertSame(['id' => null, 'name' => null, 'email' => null], UserHelper::userSummary());
        $this->assertNull(UserHelper::userShortSummary());
    }

    public function test_info_reads_only_direct_user_attributes(): void
    {
        $user = User::factory()->create([
            'name' => 'Maria da Silva',
            'email' => 'maria@example.com',
        ]);
        $user->setAttribute('profile', ['social_links' => ['instagram' => '@maria']]);

        $this->actingAs($user);

        $this->assertSame('Maria da Silva', UserHelper::info('name'));
        $this->assertSame('fallback', UserHelper::info('profile.social_links', 'fallback'));
        $this->assertSame('fallback', UserHelper::info('missing_column', 'fallback'));
    }

    public function test_user_status_can_compare_different_active_values(): void
    {
        $user = User::factory()->create();
        $user->setAttribute('active', '1');
        $user->setAttribute('status', 'active');
        $user->setAttribute('status_id', 2);

        $this->actingAs($user);

        $this->assertTrue(UserHelper::userIsActive());
        $this->assertTrue(UserHelper::userIsActive('status', 'active'));
        $this->assertTrue(UserHelper::userIsActive('status_id', 2));
        $this->assertFalse(UserHelper::userIsActive('status', 'inactive'));
        $this->assertFalse(UserHelper::userIsActive('missing_column', true));
    }

    public function test_user_name_and_email_helpers_return_display_ready_values(): void
    {
        $user = User::factory()->create([
            'name' => 'Maria da Silva',
            'email' => 'MARIA@EXAMPLE.COM',
        ]);

        $this->actingAs($user);

        $this->assertSame('Maria da Silva', UserHelper::username());
        $this->assertSame('Maria', UserHelper::userFirstName());
        $this->assertSame('Maria S.', UserHelper::userShortName());
        $this->assertSame('MARIA@EXAMPLE.COM', UserHelper::userEmail());
        $this->assertSame('EXAMPLE.COM', UserHelper::emailDomain());
        $this->assertSame([
            'id' => $user->id,
            'name' => 'Maria da Silva',
            'email' => 'MARIA@EXAMPLE.COM',
        ], UserHelper::userSummary());
        $this->assertSame('Maria S. — MARIA@EXAMPLE.COM', UserHelper::userShortSummary());
    }

    public function test_email_helpers_mask_and_sanitize_email_values(): void
    {
        $this->assertSame('********@example.com', UserHelper::maskEmail('john.doe@example.com'));
        $this->assertSame('jo******@example.com', UserHelper::maskEmail('john.doe@example.com', 6));
        $this->assertSame('****.doe@example.com', UserHelper::maskEmail('john.doe@example.com', 4, 'start'));
        $this->assertSame('john****@example.com', UserHelper::maskEmail('john.doe@example.com', 4, 'middle'));
        $this->assertSame('invalid-email', UserHelper::maskEmail('invalid-email'));
        $this->assertSame('john@example.com', UserHelper::sanitizeEmail(' JOHN@example.com '));
    }

    public function test_user_avatar_uses_attribute_path_or_placeholder(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('avatars/user.jpg', 'avatar');

        $user = User::factory()->create();
        $user->setAttribute('avatar', 'avatars/user.jpg');

        $this->actingAs($user);

        $this->assertSame('avatars/user.jpg', UserHelper::userAvatarPath());
        $this->assertSame('/storage/avatars/user.jpg', UserHelper::userAvatar());

        $user->setAttribute('avatar', null);

        $this->assertNull(UserHelper::userAvatar());
        $this->assertSame(asset('img/placeholders/avatars/default-avatar.jpg'), UserHelper::userAvatar(
            placeholder: 'img/placeholders/avatars/default-avatar.jpg'
        ));
    }

    public function test_user_avatar_fallback_uses_two_initials_and_stable_color(): void
    {
        $user = User::factory()->create([
            'name' => 'Maria da Silva',
        ]);

        $this->actingAs($user);

        $fallback = UserHelper::userAvatarFallback();

        $this->assertSame('MS', $fallback['initials']);
        $this->assertContains($fallback['color'], ['#1abc9c', '#3498db', '#9b59b6', '#e67e22', '#e74c3c']);
    }

    public function test_spatie_user_helpers_are_safe_when_has_roles_is_not_implemented(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->assertFalse(UserHelper::userHasRole('admin'));
        $this->assertFalse(UserHelper::userHasPermission('posts.edit'));
        $this->assertSame([], UserHelper::userRoles());
        $this->assertSame([], UserHelper::userPermissions());
    }

    public function test_spatie_catalog_helpers_return_registered_role_and_permission_names(): void
    {
        Role::query()->create(['name' => 'admin', 'guard_name' => 'web']);
        Permission::query()->create(['name' => 'posts.edit', 'guard_name' => 'web']);

        $this->assertSame(['admin'], UserHelper::allRoles()->all());
        $this->assertSame(['posts.edit'], UserHelper::allPermissions()->all());
    }
}
