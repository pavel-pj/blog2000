<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Catalog;
use App\Models\User;

class CatalogControllerTest extends TestCase
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

        $this->user->assignRole(['Admin','User']); 
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
    public function testCatalogStore(): void
    {   

 
        $this->actingAs($this->user);

        $postData = [
            "name" => "New Catalog"
        ];

        $response = $this->postJson('/api/catalogs', $postData);
 
        $response->assertStatus(201);
    }

    public function testCatalogStoreWithError(): void
    {   

 
        $this->actingAs($this->user);

        $postData = [
            "name" => ""
        ];

        $response = $this->postJson('/api/catalogs', $postData);
 
        $response->assertStatus(422);
    }

        public function testCatalogShow(): void
    {   

 
        $this->actingAs($this->user);

        $catalog =Catalog::create(['name'=>'catalog']);
 
        $response = $this->get("/api/catalogs/{$catalog->id}");
 
        $response->assertStatus(200);
    }


       public function testCatalogIndex(): void
    {   

  
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog"]);
        
        $response = $this->get('/api/catalogs');
        $response->assertStatus(200);
    }

    public function testCatalogDelete(): void
    {   

  
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog"]);
        
        $response = $this->delete("/api/catalogs/{$catalog->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('catalogs', ['id' => $catalog->id]);
  
    }

    public function testCatalogDeleteWithError(): void
    {   

  
        $this->actingAs($this->user);

        $catalog = 9999;
        
        $response = $this->delete("/api/catalogs/{$catalog}");

        $response->assertStatus(404);
 
    }

    public function testCatalogUpdate(): void
    {
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog"]);
 
        $postData = [
            'name' => 'NEW CATALOG-34',
        ];
        $response = $this->patchJson("/api/catalogs/{$catalog->id}", $postData );

        $response->assertStatus(200);

        // Проверяем, что данные обновились в БД
        $this->assertDatabaseHas('catalogs', [
            'id' => $catalog->id,
            'name' => 'NEW CATALOG-34' // Новое значение
        ]); 
    }

     public function testCatalogUpdateWithError(): void
    {
        $this->actingAs($this->user);

        $catalog = Catalog::create(['name'=>"new catalog"]);
 
        $postData = [
            'name' => true,
        ];
        $response = $this->patchJson("/api/catalogs/{$catalog->id}", $postData );

        $response->assertStatus(422);
 
    }

    public function testShowThrowsExceptionForNonExistentId(): void
    {
        $this->actingAs($this->user);

        $nonExistentId = '550e8400-e29b-41d4-a716-446655440000';

        // Проверяем что исключение действительно выбрасывается
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('non-existent instance');

        // Можно также протестировать через рефлексию напрямую репозиторий
        $repository = new \App\Repositories\CatalogRepository();
        $repository->show($nonExistentId);
    }
}
