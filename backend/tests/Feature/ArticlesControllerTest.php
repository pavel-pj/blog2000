<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Catalog;

class ArticlesControllerTest extends TestCase
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
            'email' => 'test@example.com',
         ]);

        $this->user->assignRole(['Admin','User']); 
    }

      // Выполняется после КАЖДОГО теста
    protected function tearDown(): void
    {
        // Очищаем ресурсы если нужно
        $this->user = null;
        
        parent::tearDown();
    }

    
    
    public function testArticleShow(): void
    {
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog24"]);
        $article = Article::create(
            [
                'name' =>'article',
                'title' =>'title',
                'slug' => 'parth-to-article' ,
                'catalog_id' => $catalog->id 
            ]
        );
        $response = $this->get('/api/articles/'. $article->id);

        $response->assertStatus(200);
    }

    public function testArticleShowNotFound()
    {
        $this->actingAs($this->user);

        $nonExistentId = 9999;
        $response = $this->getJson("/api/articles/{$nonExistentId}");
        
        // Проверяем обработку несуществующей статьи
        $response->assertStatus(404); 
    }

    public function testArticleStore(): void
    {
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog24"]);
         // Подготовка данных
        $postData = [
            'name' => 'Article 1',
            'title' => 'new test Article',
            'slug' => 'slug-eo-23',
            'catalog_id' => $catalog->id
        ];

        // Отправка POST запроса
        $response = $this->post('/api/articles', $postData);
        $response->assertStatus(201); // Проверка статуса
  
    }

    public function testArticleStoreWithValidationError()
    {
        $this->actingAs($this->user);

        // Отправка невалидных данных (без обязательных полей)
        $invalidData = [
            'name' => '', // Пустое обязательное поле
        ];

        $response = $this->postJson('/api/articles', $invalidData);
        
        // Должен вернуть 422 при ошибке валидации
        $response->assertStatus(422);
    }
 
    public function testArticleIndex(): void
    {
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog24"]);
        
        $article = Article::create(
            [
                'name' =>'article',
                'title' =>'title',
                'slug' => 'parth-to-article' ,
                'catalog_id' => $catalog->id 
            ]
        );
        $response = $this->get('/api/articles');

        $response->assertStatus(200);
    }

    public function testArticleUpdate(): void
    {
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog24"]);
        
        $article = Article::create(
            [
                'name' =>'article',
                'title' =>'title',
                'slug' => 'parth-to-article' ,
                'catalog_id' => $catalog->id 
            ]
        );

        $postData = [
            'name' => 'NEW ARTICLE',
            
        ];
        $response = $this->patchJson("/api/articles/{$article->id}", $postData );

        $response->assertStatus(200);

        // Проверяем, что данные обновились в БД
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'name' => 'NEW ARTICLE' // Новое значение
        ]); 
    }

    public function testArticleUpdateWithValidationError()
    {
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog24"]);
        
        $article = Article::create(
            [
                'name' =>'article',
                'title' =>'title',
                'slug' => 'parth-to-article' ,
                'catalog_id' => $catalog->id 
            ]
        );

        $postData = [
            'name' => '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
            
        ];
        $response = $this->patchJson("/api/articles/{$article->id}", $postData );
        
        // Должен вернуть 422 при ошибке валидации
        $response->assertStatus(422);
    }

    public function testArticleDelete(): void
    {
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog24"]);
        
        $article = Article::create(
            [
                'name' =>'article',
                'title' =>'title',
                'slug' => 'parth-to-article' ,
                'catalog_id' => $catalog->id 
            ]
        );
        $response = $this->delete("/api/articles/{$article->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('articles', [
        'id' => $article->id
    ]);

    }

    public function testArticleDeleteNotFound()
    {
        $this->actingAs($this->user);

        $nonExistentId = 9999;
        $response = $this->deleteJson("/api/articles/{$nonExistentId}");
        
        // Проверяем обработку несуществующей статьи
        $response->assertStatus(404);  
    }

    public function testShowThrowsExceptionForNonExistentId(): void
    {
        $this->actingAs($this->user);

        $nonExistentId = '550e8400-e29b-41d4-a716-446655440000';

        // Проверяем что исключение действительно выбрасывается
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('non-existent instance');

        // Можно также протестировать через рефлексию напрямую репозиторий
        $repository = new \App\Repositories\ArticleRepository();
        $repository->show($nonExistentId);
    }
        
}

  