<?php

namespace WebFresh\UserManager\Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use WebFresh\UserManager\Models\PermissionGroup;
use Database\Factories\WfumUserFactory;

class TeamsTest extends TestCase
{
    use RefreshDatabase;

    // Test teams index redirect when not logged in
    public function test_redirect_teams() {
        $this->actingAsGuest();
        $response = $this->get('admin/teams');
        $response->assertRedirect('/login');
    }

    // Test teams index when logged in
    public function test_teams_page() {
        $user = User::factory()->make();
        $this->actingAs($user);
        $response = $this->get('admin/teams');
        $response->assertStatus(200);
    }
}
