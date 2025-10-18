<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class SubjectControllerTest extends TestCase
{
    //use RefreshDatabase;
    use DatabaseTransactions; // вместо RefreshDatabase

    protected $user;
    protected $user2;

    protected $subjectUser2;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем пользователя, который будет доступен в каждом тесте
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        $this->user2 = User::factory()->create([
            'name' => 'Test User2',
            'email' => 'test2@example.com'
        ]);

        $this->subjectUser2 = Subject::create(['name'=>'French', 'user_id'=>$this->user2->id]);
    }

      // Выполняется после КАЖДОГО теста
    protected function tearDown(): void
    {
        // Очищаем ресурсы если нужно
        $this->user = null;
        
        parent::tearDown();
    }
    
    
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

        $nonExistentId = Str::uuid();
 
        $response = $this->get("/api/subjects/{$nonExistentId}");
 
        $response->assertStatus(404);
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

    public function test_show_throws_exception_for_attepmt_to_see_other_user_subjet(): void
    {
        $this->actingAs($this->user);
        $subject = Subject::create(['name'=>'English Lessons', 'user_id'=>$this->user->id]);
        $subject = Subject::create(['name'=>'French', 'user_id'=>$this->user2->id]);
 
        $response = $this->get("/api/subjects/{$subject->id}");
 
        $response->assertStatus(404);
        
    }

    public function testStore(): void
    {
        $this->actingAs($this->user);

        $postData = [
            'name'=> 'Main English',
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

    public function testIndexDontHaveRecordOtherUsers(): void
    {   
        $this->actingAs($this->user);

        $subject = Subject::create(['name'=>'English Lessons', 'user_id'=>$this->user->id]);
        $subject = Subject::create(['name'=>'French', 'user_id'=>$this->user2->id]);
        
        $response = $this->get('/api/subjects');
        $response->assertStatus(200);
 
        $responseArray = json_decode($response->getContent(), true);
         
        $user2Id = $this->user2->id;
 
        $isFrench = array_filter($responseArray, function ($item)  use ($user2Id){
            return $item['user_id'] === $user2Id;
        });
 
        $this->assertEquals( $isFrench, []);
         
    }
 


    public function testDelete(): void
    {   

  
        $this->actingAs($this->user);

        $subject = Subject::create(['name'=>'English Lessons', 'user_id'=>$this->user->id]);
        
        $response = $this->delete("/api/subjects/{$subject->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
  
    }

    public function testSubjectDeleteWithError(): void
    {   

  
        $this->actingAs($this->user);

        $subject = 9999;
        
        $response = $this->delete("/api/subjects/{$subject}");

        $response->assertStatus(404);
 
    }

    public function testSubjectUpdate(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English lesson",
            'user_id' => $this->user->id,
        ]);
         
        $postData = [
            'name' => 'NEW ARTICLE',
            
        ];
        $response = $this->patchJson("/api/subjects/{$subject->id}", $postData );

        $response->assertStatus(200);

        // Проверяем, что данные обновились в БД
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'NEW ARTICLE' // Новое значение
        ]); 
    }

    public function testSubjectUpdateWithValidationError()
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English lesson",
            'user_id' => $this->user->id,
        ]);
         

        $postData = [
            'name' => '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
            
        ];
        $response = $this->patchJson("/api/subjects/{$subject->id}", $postData );
        
        // Должен вернуть 422 при ошибке валидации
        $response->assertStatus(422);
    }
         
    
}
