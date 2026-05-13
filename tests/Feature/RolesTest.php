<?php

namespace WebFresh\UserManager\Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use WebFresh\UserManager\Models\PermissionGroup;
use Database\Factories\WfumUserFactory;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    // Test roles index redirect when not logged in
    public function test_redirect_roles() {
        $this->actingAsGuest();
        $response = $this->get('admin/roles');
        $response->assertRedirect('/login');
    }

    // Test roles index when logged in
    public function test_roles_page() {
        $user = User::factory()->make();
        $this->actingAs($user);
        $response = $this->get('admin/roles');
        $response->assertStatus(200);
    }
}
