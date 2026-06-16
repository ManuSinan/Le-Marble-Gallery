<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MobileAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_mobile_number_and_password(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->post(route('signup.store'), [
            'name' => 'Student User',
            'mobile' => '9876543222',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'name' => 'Student User',
            'mobile' => '9876543222',
            'username' => '9876543222',
            'role_id' => 1,
            'status' => 'active',
            'mobile_verified' => 1,
        ]);
    }

    public function test_user_can_sign_in_with_mobile_number(): void
    {
        $this->seed(DatabaseSeeder::class);

        User::create([
            'name' => 'Student User',
            'username' => '9876543233',
            'mobile' => '9876543233',
            'password' => Hash::make('secret123'),
            'role_id' => 1,
            'status' => 'active',
            'mobile_verified' => 1,
            'email_verified' => 0,
        ]);

        $response = $this->post(route('signin'), [
            'login' => '9876543233',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_admin_can_sign_in_and_is_redirected_to_acp(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->post(route('signin'), [
            'login' => 'admin',
            'password' => '123456',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }
}
