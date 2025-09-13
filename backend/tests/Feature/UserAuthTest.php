<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testUserRegister(): void
    {
        $postData = [
            'email' => 'test2009@mail.ru',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];
        
        $response = $this->post('/api/register',$postData);
            
        $response->assertStatus(201);
    }

    public function testUserRegisterWrongRegister(): void
    {
        $postData = [
            'email' => '',
        ];
        
        $response = $this->post('/api/register',$postData);
            
        $response->assertStatus(422);
    }
}
