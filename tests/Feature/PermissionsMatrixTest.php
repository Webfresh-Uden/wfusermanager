<?php

namespace WebFresh\UserManager\Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use WebFresh\UserManager\Models\PermissionGroup;
use Database\Factories\WfumUserFactory;

class PermissionsMatrixTest extends TestCase
{
    use RefreshDatabase;

    // Test permissions matrix index redirect when not logged in
    public function test_redirect_permissions_matrix() {
        $this->actingAsGuest();
        $response = $this->get('admin/permissions-matrix');
        $response->assertRedirect('/login');
    }

    // Test permissions-matrix index when logged in
    public function test_permissions_matrix_page() {
        $user = User::factory()->make();
        $this->actingAs($user);
        $response = $this->get('admin/permissions-matrix');
        $response->assertStatus(200);
    }
}
