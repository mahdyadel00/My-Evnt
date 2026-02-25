<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryFeatureTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_categories()
    {
        $response = $this->get('/admin/event_categories');

        $response->assertStatus(200);
    }
}
