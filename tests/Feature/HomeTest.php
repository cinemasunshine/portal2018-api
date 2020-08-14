<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * @return void
     */
    public function testHome()
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('home');
    }
}
