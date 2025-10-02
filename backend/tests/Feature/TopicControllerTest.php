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

     public function testTopicStore(): void
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

    public function testTopicStoreErrorWhenOtherUserSubjectIsUsed(): void
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

    public function testTopicStoreErrorUnExistedSubject(): void
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

    public function testTopicStoreSameNameForOtherSubjects(): void
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

     public function testTopicStoreDublicate(): void
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
     
    public function testTopicIndex(): void
    {
        $this->actingAs($this->user);

        $topic = Topic::create([
            'name' => 'nouns',
            'subject_id' => $this->subject->id
        ]);
       
        // Отправка POST запроса
        $response = $this->get('/api/subjects/'.$this->subject->id.'/topics');
        $data = json_decode($response->getContent(), true);
        $response->assertStatus(200);     
        $founded = array_filter($data['data'], function ($item) use ($topic) 
        {
            return $item['id'] === $topic->id;
        });
 
        $isFounded = false;
        if ($founded) {
            $isFounded =true;
        }

        $this->assertTrue($isFounded); 
 
  
    } 

         
    public function testTopicIndexNoTopicOtherUsers(): void
    {
        $this->actingAs($this->user);

        $topic = Topic::create([
            'name' => 'nouns',
            'subject_id' => $this->subject2->id
        ]);
       
        // Отправка POST запроса
        $response = $this->get('/api/subjects/'.$this->subject->id.'/topics');
        $data = json_decode($response->getContent(), true);
        $response->assertStatus(200);     
        $founded = array_filter($data['data'], function ($item) use ($topic) 
        {
            return $item['id'] === $topic->id;
        });
 
        $isFounded = false;
        if ($founded) {
            $isFounded =true;
        }

        $this->assertEquals(false,$isFounded); 
 
  
    }


    
        public function testTopicIndexErrorWithOtherUserSubject(): void
    {
        $this->actingAs($this->user);

        $topic = Topic::create([
            'name' => 'nouns',
            'subject_id' => $this->subject2->id
        ]);
       
        // Отправка POST запроса
        $response = $this->get('/api/subjects/'.$this->subject2->id.'/topics');
        $response->assertStatus(404);  
 
  
    } 

    public function testTopicIndexErrorIncorrectSubject(): void
    {
        $this->actingAs($this->user);
 
        // Отправка POST запроса
        $response = $this->get('/api/subjects/'.Str::uuid().'/topics');
        $response->assertStatus(404);  
 
  
    } 

    public function testTopicShow(): void
    {
        $this->actingAs($this->user);
 
        $topic = Topic::create(
            [
                'name' =>'get out',
                'subject_id' => $this->subject->id 
            ]
        );
        $response = $this->get('/api/topics/'. $topic->id);

        $response->assertStatus(200);
    }

    public function testTopicShowErrorOtherUserSubject(): void
    {
        $this->actingAs($this->user);
 
        $topic2 = Topic::create(
            [
                'name' =>'get out',
                'subject_id' => $this->subject2->id 
            ]
        );
        $response = $this->get('/api/topics/'. $topic2->id);

        $response->assertStatus(404);
    }
    
    public function testTopicShowErrorIncorrectId(): void
    {
        $this->actingAs($this->user);
 
        $response = $this->get('/api/topics/'. Str::uuid());

        $response->assertStatus(404);
    }

   public function testTopicUpdate(): void
    {
        $this->actingAs($this->user);
 
        $topic = Topic::create(
            [
                'name' =>'verbs',
                'subject_id' => $this->subject->id 
            ]
        );

        $postData = [
            'name' => 'NEW VALUE',
            
        ];
        $response = $this->patchJson("/api/topics/{$topic->id}", $postData );

        $response->assertStatus(200);

        // Проверяем, что данные обновились в БД
        $this->assertDatabaseHas('topics', [
            'id' => $topic->id,
            'name' => 'NEW VALUE' // Новое значение
        ]); 
    }

    public function testTopicUpdateErrorOtherUserTopic(): void
    {
        $this->actingAs($this->user);
 
        $topic = Topic::create(
            [
                'name' =>'verbs',
                'subject_id' => $this->subject2->id 
            ]
        );

        $postData = [
            'name' => 'NEW VALUE',
            
        ];
        $response = $this->patchJson("/api/topics/{$topic->id}", $postData );

        $response->assertStatus(404);
 
    }

    public function testTopicUpdateErrorIncorrectTopic(): void
    {
        $this->actingAs($this->user);
 
        $postData = [
            'name' => 'NEW VALUE',
            
        ];
        $response = $this->patchJson("/api/topics/".Str::uuid(), $postData );

        $response->assertStatus(404);
 
    }

    public function testTopicDelete(): void
    {
        $this->actingAs($this->user);
 
        $topic= Topic::create(
            [
                'name' =>'get out',
                'subject_id' => $this->subject->id 
            ]
        );
        $response = $this->delete("/api/topics/{$topic->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('topics', [
        'id' => $topic->id
    ]);

    }

    
    public function testTopicDeleteOtherUsersTopic(): void
    {
        $this->actingAs($this->user);
 
        $topic= Topic::create(
            [
                'name' =>'get out',
                'subject_id' => $this->subject2->id 
            ]
        );
        $response = $this->delete("/api/topics/{$topic->id}");

        $response->assertStatus(404);
 

    }

    public function testTopicDeleteIncorrectTopicId(): void
    {
        $this->actingAs($this->user);
  
        $response = $this->delete("/api/topics/".Str::uuid());

        $response->assertStatus(404);
 

    }
    
}
