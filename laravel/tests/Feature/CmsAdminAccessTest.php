<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_visiting_admin_is_redirected_to_cms_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_guest_visiting_admin_content_is_redirected_to_cms_login(): void
    {
        $this->get('/admin/content/services')->assertRedirect(route('admin.login'));
    }

    public function test_active_user_can_view_admin_dashboard(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'a-long-password-123',
            'is_active' => true,
        ]);

        $this->actingAs($user)->get('/admin')->assertOk();
    }
}
