<?php

namespace WebFresh\UserManager\Tests\Feature;

use WebFresh\UserManager\Models\WfumUser;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    // Test users index redirect when not logged in
    public function test_redirect_users() {
        $this->actingAsGuest();
        $response = $this->get('admin/users');
        $response->assertRedirect('/login');
    }

    // Test users index when logged in
    public function test_users_page() {
        $user = WfumUser::factory()->create();
        $this->actingAs($user);
        $response = $this->get('admin/users');
        $response->assertStatus(200);
    }
}
