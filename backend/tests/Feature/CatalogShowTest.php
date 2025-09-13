<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CatalogShowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCatalogShow(): void
    {   
        
        $response = $this->get('/catalog');

        $response->assertStatus(200);
    }
}
