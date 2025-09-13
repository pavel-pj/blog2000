<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DictionaryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем пользователя, который будет доступен в каждом тесте
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
    }

      // Выполняется после КАЖДОГО теста
    protected function tearDown(): void
    {
        // Очищаем ресурсы если нужно
        $this->user = null;
        
        parent::tearDown();
    }
    /**
     * A basic feature test example.
     */
    public function testDictionaryIndex(): void
    {
        $this->actingAs($this->user);
 
        $response = $this->get('/api/dictionaries?typeDictionary=catalog');
        $response->assertStatus(200);
    }
}
