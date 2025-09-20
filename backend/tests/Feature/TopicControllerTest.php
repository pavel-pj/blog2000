<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use App\Models\Subject;
use Illuminate\Support\Str;

 

class TopicControllerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем пользователя, который будет доступен в каждом тесте
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
 
        $this->user2 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test2@example.com'
        ]);

        
        $this->subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);

        
        $this->subject2 = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user2->id
        
        ]);



    }

      // Выполняется после КАЖДОГО теста
    protected function tearDown(): void
    {
        // Очищаем ресурсы если нужно
        $this->user = null;
        
        parent::tearDown();
    }

     public function testWordStore(): void
    {
        $this->actingAs($this->user);

        
         // Подготовка данных
        $postData = [
            'name' => 'nouns',
            'subject_id' => $this->subject->id
        ];

        // Отправка POST запроса
        $response = $this->post('/api/topics', $postData);
        $response->assertStatus(201); // Проверка статуса

        $data = json_decode($response->getContent(), true);
        $isDataInDb = Topic::where('id',$data['id'])->exists();
        $this->assertTrue($isDataInDb, true);
  
    }

    public function testWordStoreErrorWhenOtherUserSubjectIsUsed(): void
    {
        $this->actingAs($this->user);

        
         // Подготовка данных
        $postData = [
            'name' => 'nouns',
            'subject_id' => $this->subject2->id
        ];

        // Отправка POST запроса
        $response = $this->post('/api/topics', $postData);
        $response->assertStatus(422); // Проверка статуса
 
  
    }

    public function testWordStoreErrorUnexistedSubject(): void
    {
        $this->actingAs($this->user);

        
         // Подготовка данных
        $postData = [
            'name' => 'nouns',
            'subject_id' => Str::uuid()
        ];

        // Отправка POST запроса
        $response = $this->post('/api/topics', $postData);
        $response->assertStatus(422); // Проверка статуса
 
  
    }

    public function testWordStoreSameNameForOtherSubjects(): void
    {
        $this->actingAs($this->user);
        
        
         // Подготовка данных
        $postData = [
            'name' => 'nouns',
            'subject_id' => $this->subject->id
        ];

        $subject3 = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);
        $postData2 = [
            'name' => 'nouns',
            'subject_id' => $subject3->id
        ];

        // Отправка POST запроса
        $response = $this->post('/api/topics', $postData);
        $response->assertStatus(201);  
        $response2 = $this->post('/api/topics', $postData2);
        $response2->assertStatus(201); // Проверка статуса
 
  
    }

     public function testWordStoreDublicate(): void
    {
        $this->actingAs($this->user);
        
        
         // Подготовка данных
        $postData = [
            'name' => 'nouns',
            'subject_id' => $this->subject->id
        ];
 
        // Отправка POST запроса
        $response = $this->post('/api/topics', $postData);
        $response2 = $this->post('/api/topics', $postData);
        $response2->assertStatus(422);  
 
  
    }
    /*
    public function testWordIndex(): void
    {
        $this->actingAs($this->user);

        $topic = Topic::create([
            'name' => 'nouns',
            'subject_id' => $this->subject->id
        ]);
       
        // Отправка POST запроса
        $response = $this->post('/api/subjects/'.$this->subject->id.'/topics');
        $data = json_decode($response->getContent(), true);
        dd($data);
        $response->assertStatus(200);  
 
  
    }*/
}
