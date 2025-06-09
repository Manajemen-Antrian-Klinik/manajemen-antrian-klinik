<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{   
    /** @test */
    public function test_user_registration()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'john@gmail.com']);
    }

    /** @test */
    public function test_registration_with_invalid_email()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'invalid@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_registration_with_short_password() {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);
        
        $response->assertSessionHasErrors('password');
    }
    
    /** @test */
    public function test_registration_with_mismatched_passwords()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password_confirmation');
    }
}