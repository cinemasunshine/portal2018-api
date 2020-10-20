<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * @group feature
 */
class HomeTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testHome()
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('home');
    }
}
