<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_match()
    {
        $response = $this->get('/api/match/1');

        $response->assertStatus(200);
    }
}
