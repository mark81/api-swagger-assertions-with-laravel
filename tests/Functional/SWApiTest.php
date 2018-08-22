<?php

namespace Tests\Functional;

use Tests\TestCase;
use Tests\Concerns\InteractsWithSwagger;

class SWApiTest extends TestCase
{
    use InteractsWithSwagger;

    /**
     * @test
     */
    public function list_swepisodes()
    {
        $this->assertGetSchemaMatch('api/v1/swepisodes');
        $response = $this->getResponseArray();
        $this->assertArrayHasKey(6, $response);
        $this->assertArrayHasKey('title', $response[6]);
        $this->assertEquals($response[6]['title'], "X-Wing.");
    }
}
