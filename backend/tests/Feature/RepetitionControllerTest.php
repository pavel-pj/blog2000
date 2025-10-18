<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Repetition;
use App\Models\Subject;
use App\Models\Word;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

class RepetitionControllerTest extends TestCase
{
    //use RefreshDatabase;
    use DatabaseTransactions; // вместо RefreshDatabase

    protected $user1;
    protected $user2;

    protected $subject1;
    protected $subject2;
    protected $word1_a1;
    protected $word2_a1;

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

        $this->subject1 = Subject::create(['name'=>'user 1 Subject', 'user_id'=>$this->user1->id]);
        $this->subject2 = Subject::create(['name'=>'User 2 FOFO-200', 'user_id'=>$this->user2->id]);

        $this->word1_a1 = Word::create(['name'=>'test word', 'subject_id'=>$this->subject1->id]);
        $this->word2_a1 = Word::create(['name'=>'test24', 'subject_id'=>$this->subject2->id]);


    }

      // Выполняется после КАЖДОГО теста
    protected function tearDown(): void
    {
        // Очищаем ресурсы если нужно
      
        
        parent::tearDown();
    }

    public function testShow(): void
    {
        /*
        $this->actingAs($this->user1);

        $repetition = Repetition::create([
            'subject_id' => $this->subject1->id 
        ]);
        
        
        
 
        $response = $this->get("/api/repetitions/{$repetition->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' =>$this->word1_a1->id
        ]);
 



    }
    */
}
