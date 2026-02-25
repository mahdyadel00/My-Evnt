<?php

namespace Tests\Unit;

use App\Models\EventCategory;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_get_all_categories()
    {
        $categories = EventCategory::all();

        $this->assertNotEmpty($categories);
    }
}
