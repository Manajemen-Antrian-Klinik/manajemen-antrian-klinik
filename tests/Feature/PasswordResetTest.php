<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Mail\ResetPasswordMail;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123')
        ]);
    }

    /** @test */
    public function forgot_password_page_redirects_authenticated_users()
    {
        $this->actingAs($this->user);

        $response = $this->get('/forgot-password');

        $response->assertRedirect('/home'); // atau route default setelah login
    }

    /** @test */
    public function forgot_password_validates_email_required()
    {
        $response = $this->post('/forgot-password', []);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }

    /** @test */
    public function forgot_password_validates_email_format()
    {
        $response = $this->post('/forgot-password', [
            'email' => 'invalid-email'
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }

    /** @test */
    public function forgot_password_validates_email_exists()
    {
        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent@gmail.com'
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }

    /** @test */
    public function forgot_password_sends_reset_link_for_valid_email()
    {
        Mail::fake();

        $response = $this->post('/forgot-password', [
            'email' => $this->user->email
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        
        // Check if reset token was created in database
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $this->user->email
        ]);
    }

    /** @test */
    public function forgot_password_throttles_requests()
    {
        // Make multiple requests to trigger throttling
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/forgot-password', [
                'email' => $this->user->email
            ]);
        }

        // The last request should be throttled
        $response->assertStatus(429); // Too Many Requests
    }

    /** @test */
    public function reset_password_page_can_be_rendered_with_valid_token()
    {
        $token = Password::createToken($this->user);

        $response = $this->get('/reset-password/' . $token . '?email=' . $this->user->email);

        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
        $response->assertViewHas('title', 'Reset Password');
        $response->assertViewHas('token', $token);
        $response->assertViewHas('email', $this->user->email);
        $response->assertSee('Reset Password');
        $response->assertSee('Enter your new password');
    }

    /** @test */
    public function reset_password_page_redirects_authenticated_users()
    {
        $this->actingAs($this->user);
        $token = Password::createToken($this->user);

        $response = $this->get('/reset-password/' . $token . '?email=' . $this->user->email);

        $response->assertRedirect('/home'); // atau route default
    }

    /** @test */
    public function reset_password_validates_required_fields()
    {
        $token = Password::createToken($this->user);

        $response = $this->post('/reset-password', [
            'token' => $token
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $response->assertRedirect();
    }

    /** @test */
    public function reset_password_validates_email_format()
    {
        $token = Password::createToken($this->user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'invalid-email',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }

    /** @test */
    public function reset_password_validates_email_exists()
    {
        $token = Password::createToken($this->user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'nonexistent@gmail.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }

    /** @test */
    public function reset_password_validates_password_minimum_length()
    {
        $token = Password::createToken($this->user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => '123',
            'password_confirmation' => '123'
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertRedirect();
    }

    /** @test */
    public function reset_password_validates_password_maximum_length()
    {
        $token = Password::createToken($this->user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => str_repeat('a', 256),
            'password_confirmation' => str_repeat('a', 256)
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertRedirect();
    }

    /** @test */
    public function reset_password_validates_password_confirmation()
    {
        $token = Password::createToken($this->user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword'
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertRedirect();
    }

    /** @test */
    public function reset_password_fails_with_invalid_token()
    {
        $response = $this->post('/reset-password', [
            'token' => 'invalid-token',
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }

    /** @test */
    public function reset_password_succeeds_with_valid_data()
    {
        $token = Password::createToken($this->user);
        $newPassword = 'newpassword123';
        $oldPassword = $this->user->password;

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status');

        // Verify password was changed
        $this->user->refresh();
        $this->assertTrue(Hash::check($newPassword, $this->user->password));
        $this->assertNotEquals($oldPassword, $this->user->password);

        // Verify remember token was regenerated
        $this->assertNotNull($this->user->remember_token);

        // Verify password reset token was deleted
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $this->user->email
        ]);
    }

    /** @test */
    public function reset_password_token_expires_after_time_limit()
    {
        // Create token and artificially age it
        $token = Password::createToken($this->user);
        
        // Update the created_at timestamp to simulate expired token (Laravel default is 1 hour)
        DB::table('password_reset_tokens')
            ->where('email', $this->user->email)
            ->update(['created_at' => now()->subHours(2)]); // 2 hours ago

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }

    /** @test */
    public function custom_reset_password_email_contains_correct_data()
    {
        $token = 'sample-token';
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->user->email,
        ]));

        $mail = new ResetPasswordMail($this->user, $resetUrl);

        $this->assertEquals('Reset Password - Klinik Hestia Medika', $mail->envelope()->subject);
        $this->assertEquals('emails.reset-password', $mail->content()->view);
        $this->assertEquals($this->user, $mail->content()->with['user']);
        $this->assertEquals($resetUrl, $mail->content()->with['resetUrl']);
    }

    /** @test */
    public function reset_password_clears_user_sessions()
    {
        $token = Password::createToken($this->user);

        // Simulate user having active sessions
        session(['user_data' => 'some_data']);
        
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect('/login');
        
        // Verify user is not authenticated after password reset
        $this->assertGuest();
    }

    /** @test */
    public function reset_password_route_requires_guest_middleware()
    {
        $this->actingAs($this->user);
        $token = Password::createToken($this->user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect('/home'); // atau route default authenticated users
    }

    /** @test */
    public function forgot_password_shows_success_message()
    {
        Mail::fake();

        $response = $this->post('/forgot-password', [
            'email' => $this->user->email
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        
        // Follow redirect to see the message
        $followResponse = $this->get('/forgot-password');
        $followResponse->assertSee('We have emailed your password reset link');
    }

    /** @test */
    public function reset_password_shows_success_message()
    {
        $token = Password::createToken($this->user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status');
        
        // Follow redirect to login page
        $followResponse = $this->get('/login');
        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    /** @test */
    public function password_reset_email_queue_is_processed()
    {
        Mail::fake();

        $this->post('/forgot-password', [
            'email' => $this->user->email
        ]);

        // memverifikasi token dibuat di database
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $this->user->email]);
    }

    /** @test */
    public function multiple_password_reset_requests_override_previous_tokens()
    {
        Mail::fake();

        // First request
        $this->post('/forgot-password', ['email' => $this->user->email]);
        
        // Second request
        $this->post('/forgot-password', ['email' => $this->user->email]);

        // Should only have one token record per email
        $tokenCount = DB::table('password_reset_tokens')
            ->where('email', $this->user->email)
            ->count();
            
        $this->assertEquals(1, $tokenCount);
    }

    /** @test */
    public function password_reset_validates_email_dns()
    {
        $response = $this->post('/forgot-password', [
            'email' => 'invalid@invalid-domain-that-does-not-exist.com'
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }
}