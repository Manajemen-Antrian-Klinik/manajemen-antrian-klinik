<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserLoginTest extends TestCase
{   
    use RefreshDatabase;
    
    /** @test */
    public function test_user_login()
    {   
        // Buat user terlebih dahulu
        User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home');  // Redirect to home after login
    }

    /** @test */
    public function test_user_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();  // Harus gagal login
    }
}