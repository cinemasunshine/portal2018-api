<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * @group feature
 */
class HomeTest extends TestCase
{
    /**
     * @test
     */
    public function testHome(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('home');
    }
}
