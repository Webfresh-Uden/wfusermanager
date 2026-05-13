<?php

namespace WebFresh\UserManager\Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use WebFresh\UserManager\Models\PermissionGroup;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    // Test permissions index redirect when not logged in
    public function test_redirect_permissions() {
        $this->actingAsGuest();
        $response = $this->get('admin/permissions');
        $response->assertRedirect('/login');
    }

    // Test permissions index when logged in
    public function test_permissions_page() {
        $user = User::factory()->make();
        $this->actingAs($user);
        $response = $this->get('admin/permissions');
        $response->assertStatus(200);
    }
}
