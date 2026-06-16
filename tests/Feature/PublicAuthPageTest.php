<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAuthPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_signin_page_renders_website_auth_copy(): void
    {
        $response = $this->get(route('signin'));

        $response->assertOk();
        $response->assertSee('Sign In');
        $response->assertSee('Forgot password');
    }

    public function test_signup_page_renders_website_registration_copy(): void
    {
        $response = $this->get(route('signup'));

        $response->assertOk();
        $response->assertSee('Create Account');
        $response->assertSee('Already have an account?');
    }

    public function test_password_reset_page_renders_request_step_by_default(): void
    {
        $response = $this->get(route('password.reset'));

        $response->assertOk();
        $response->assertSee('Reset your password in two simple steps.');
        $response->assertSee('Request OTP');
    }

    public function test_password_reset_page_renders_verify_step_when_session_exists(): void
    {
        $response = $this->withSession([
            'password_reset_email' => '9876543210',
        ])->get(route('password.reset'));

        $response->assertOk();
        $response->assertSee('Enter OTP');
        $response->assertSee('9876543210');
    }
}
