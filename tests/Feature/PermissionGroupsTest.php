<?php

namespace WebFresh\UserManager\Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use WebFresh\UserManager\Models\PermissionGroup;
use Database\Factories\WfumUserFactory;

class PermissionGroupsTest extends TestCase
{
    use RefreshDatabase;

    // Test permission groups index redirect when not logged in
    public function test_redirect_permission_groups() {
        $this->actingAsGuest();
        $response = $this->get('admin/permission-groups');
        $response->assertRedirect('/login');
    }

    // Test permission-groups index when logged in
    public function test_permission_groups_page() {
        $user = User::factory()->make();
        $this->actingAs($user);
        $response = $this->get('admin/permission-groups');
        $response->assertStatus(200);
    }
}
