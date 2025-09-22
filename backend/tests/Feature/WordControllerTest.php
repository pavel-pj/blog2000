<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Word;
use App\Models\Subject;
use Illuminate\Support\Str;
 

class WordControllerTest extends TestCase
{
    use RefreshDatabase;
   // use DatabaseTransactions; // вместо RefreshDatabase

    protected $user;

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
    }

      // Выполняется после КАЖДОГО теста
    protected function tearDown(): void
    {
        // Очищаем ресурсы если нужно
        $this->user = null;
        
        parent::tearDown();
    }
    
    public function testWordShow(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);
        $word = Word::create(
            [
                'name' =>'get out',
                'subject_id' => $subject->id 
            ]
        );
        $response = $this->get('/api/words/'. $word->id);

        $response->assertStatus(200);
    }
    
    public function testWordShowNotFound()
    {
        $this->actingAs($this->user);

        $nonExistentId =  Str::uuid();
        $response = $this->getJson("/api/words/{$nonExistentId}");
        
        // Проверяем обработку несуществующей статьи
        $response->assertStatus(404); 
    }

        
    public function testErrorWhenTryToShowWordOfWordOtherUser()
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user2->id
        
        ]);
        $word = Word::create(
            [
                'name' =>'get out',
                'subject_id' => $subject->id 
            ]
        );
        $response = $this->getJson("/api/words/{$word->id}");
        
        // Проверяем обработку несуществующей статьи
        $response->assertStatus(404); 
    }
    
    public function testWordStore(): void
    {
        $this->actingAs($this->user);

         $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);
         // Подготовка данных
        $postData = [
            'name' => 'get out',
            'subject_id' => $subject->id
        ];

        // Отправка POST запроса
        $response = $this->post('/api/words', $postData);
        $response->assertStatus(201); // Проверка статуса
  
    }
     
    public function testWordStoreWithValidationError()
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);
         // Подготовка данных
        $invalidData = [
            'name' => ''
          
        ];

        $response = $this->postJson('/api/words', $invalidData);
        
        // Должен вернуть 422 при ошибке валидации
        $response->assertStatus(422);
    }

    public function testWordStoreToOtherUserSubjectId()
    {
        $this->actingAs($this->user);

        $subject2 = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user2->id
        
        ]);
         // Подготовка данных
        $postData = [
            'name' => 'get out',
            'subject_id' => $subject2->id
        ];

        $response = $this->postJson('/api/words', $postData);
        
        // Должен вернуть 422 при ошибке валидации
        $response->assertStatus(422);
    }
 
    public function testWordIndex(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);
        
        $word = Word::create([
            'name' => 'get out',
            'subject_id' => $subject->id
        ]);
 
        $response = $this->get('/api/subjects/'.$subject->id.'/words');
        $data = json_decode($response->getContent(), true);

        $usersWord = array_filter($data, function ($item) use ($word) {
            return $word->id === $item['id'];
        });

        //dd($data);

        $isExistWordInIndex = false;
        if ($usersWord) {
           $isExistWordInIndex = true; 
        }

        $this->assertTrue($isExistWordInIndex);

        $response->assertStatus(200);
    }

    public function testWordIndexOnlyWordsBelongsToUser(): void
    {
        $this->actingAs($this->user);

        $subject2 = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user2->id
        
        ]);

       $word = Word::create([
            'name' => 'get out',
            'subject_id' => $subject2->id
        ]);
        $response = $this->get('/api/subjects/'.$subject2->id.'/words');

        $response->assertStatus(404);
    }

  
    public function testWordUpdate(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);
        $word = Word::create(
            [
                'name' =>'get out',
                'subject_id' => $subject->id 
            ]
        );

        $postData = [
            'name' => 'NEW VALUE',
            
        ];
        $response = $this->patchJson("/api/words/{$word->id}", $postData );

        $response->assertStatus(200);

        // Проверяем, что данные обновились в БД
        $this->assertDatabaseHas('words', [
            'id' => $word->id,
            'name' => 'NEW VALUE' // Новое значение
        ]); 
    }
    
    public function testWordUpdateWithValidationError()
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);
        $word = Word::create(
            [
                'name' =>'get out',
                'subject_id' => $subject->id 
            ]
        );

        $postData = [
            'name' => 'NEW VALUE',
            
        ];
        $response = $this->patchJson("/api/words/{$word->id}", $postData );

        $postData = [
            'name' => '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
            
        ];
        $response = $this->patchJson("/api/words/{$word->id}", $postData );
        
        // Должен вернуть 422 при ошибке валидации
        $response->assertStatus(422);
    }

        public function testWordUpdateErrorForOtherUserWord(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user2->id
        
        ]);
        $word = Word::create(
            [
                'name' =>'get out',
                'subject_id' => $subject->id 
            ]
        );

        $postData = [
            'name' => 'NEW VALUE',
            
        ];
        $response = $this->patchJson("/api/words/{$word->id}", $postData );

        $response->assertStatus(422);

        // Проверяем, что данные обновились в БД
        $this->assertDatabaseHas('words', [
            'id' => $word->id,
            'name' => 'get out' // СТАРОЕ ЗНАЧЕНИЕ
        ]); 
    }
 
    public function testWordDelete(): void
    {
        $this->actingAs($this->user);

       $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user->id
        
        ]);
        $word = Word::create(
            [
                'name' =>'get out',
                'subject_id' => $subject->id 
            ]
        );
        $response = $this->delete("/api/words/{$word->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('words', [
        'id' => $word->id
    ]);

    }

    public function testWordDeleteErrorWhenOtherUsersWord(): void
    {
        $this->actingAs($this->user);

       $subject = Subject::create([
            'name'=>"English language",
            'user_id'=> $this->user2->id
        
        ]);
        $word = Word::create(
            [
                'name' =>'get out',
                'subject_id' => $subject->id 
            ]
        );
        $response = $this->delete("/api/words/{$word->id}");

        $response->assertStatus(404);
 
    }

  
    public function testArticleDeleteNotFound()
    {
        $this->actingAs($this->user);

        $nonExistentId =  Str::uuid();
        $response = $this->deleteJson("/api/words/{$nonExistentId}");
        
        // Проверяем обработку несуществующей статьи
        $response->assertStatus(404);  
    }
/*
    public function testShowThrowsExceptionForNonExistentId(): void
    {
        $this->actingAs($this->user);

        $nonExistentId = '550e8400-e29b-41d4-a716-446655440000';

        // Проверяем что исключение действительно выбрасывается
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('non-existent instance');

        // Можно также протестировать через рефлексию напрямую репозиторий
        $repository = new \App\Repositories\WordRepository();
        $repository->show($nonExistentId);
    }
        */
        
       
}

  