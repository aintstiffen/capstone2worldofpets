<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DogFactsTest extends TestCase
{
    /**
     * Test that the homepage loads with dog facts JavaScript
     *
     * @return void
     */
    public function test_homepage_includes_dog_facts_js()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('dog-facts.js');
        $response->assertSee('dog-facts.css');
    }
}