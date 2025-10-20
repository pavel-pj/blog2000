<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class SubjectOptionsControllerTest extends TestCase
{
    use DatabaseTransactions; // вместо RefreshDatabase

    protected $user1;
    protected $user2;

    protected $subject1_user1;
    protected $subject1_user2;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем пользователя, который будет доступен в каждом тесте
        $this->user1 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        $this->user2 = User::factory()->create([
            'name' => 'Test User2',
            'email' => 'test2@example.com'
        ]);
        $this->subject1_user1 = Subject::create(['name'=>'French', 'user_id'=>$this->user1->id]);
        $this->subject1_user2 = Subject::create(['name'=>'user2 Subject', 'user_id'=>$this->user2->id]);
    }

      // Выполняется после КАЖДОГО теста
    protected function tearDown(): void
    {
        // Очищаем ресурсы если нужно
        $this->user1 = null;
        
        parent::tearDown();
    }

    public function testShowSubjectOptionTest():void{
        /*
        $this->actingAs($this->user1);
  
        $response = $this->get("/api/options/{$this->subject1_user1->id}");

        //$response->assertStatus(200);
        $data = json_decode($response->getContent());
        dd($data);
        //Default values 
        $this->assertEquals($data->new_words,2);
        */
    }



}
