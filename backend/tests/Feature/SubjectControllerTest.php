<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Subject;
use App\Models\User;

class SubjectControllerTest extends TestCase
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
    public function testShow(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::create(['name'=>'English Lessons', 'user_id'=>$this->user->id]);
 
        $response = $this->get("/api/subjects/{$subject->id}");
 
        $response->assertStatus(200);
    }

        public function testShowWithError(): void
    {
        $this->actingAs($this->user);

        $subject = 99999;
 
        $response = $this->get("/api/subjects/{$subject}");
 
        $response->assertStatus(404);
    }

    public function testStore(): void
    {
        $this->actingAs($this->user);

        $postData = [
            'name'=> 'Main English',
            'user_id' => $this->user->id
        ];
 
        $response = $this->post("/api/subjects", $postData);
 
        $response->assertStatus(201);
    }

    public function testStoreWitError(): void
    {
        $this->actingAs($this->user);

        $postData = [
            'name'=> '',
         
        ];
 
        $response = $this->post("/api/subjects", $postData);
 
        $response->assertStatus(422);
    }

 

    public function testIndex(): void
    {   
        $this->actingAs($this->user);

        $subject = Subject::create(['name'=>'English Lessons', 'user_id'=>$this->user->id]);
        $subject = Subject::create(['name'=>'English Lessons2', 'user_id'=>$this->user->id]);
        
        $response = $this->get('/api/subjects');
        $response->assertStatus(200);
    }

    public function test_show_throws_exception_for_non_existent_id(): void
    {
        $this->actingAs($this->user);

        $nonExistentId = '550e8400-e29b-41d4-a716-446655440000';

        // Проверяем что исключение действительно выбрасывается
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('non-existent instance');

        // Можно также протестировать через рефлексию напрямую репозиторий
        $repository = new \App\Repositories\SubjectRepository();
        $repository->show($nonExistentId);
    }


    public function testDelete(): void
    {   

  
        $this->actingAs($this->user);

        $subject = Subject::create(['name'=>'English Lessons', 'user_id'=>$this->user->id]);
        
        $response = $this->delete("/api/subjects/{$subject->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
  
    }

    public function testCatalogDeleteWithError(): void
    {   

  
        $this->actingAs($this->user);

        $subject = 9999;
        
        $response = $this->delete("/api/subjects/{$subject}");

        $response->assertStatus(404);
 
    }
    
}
