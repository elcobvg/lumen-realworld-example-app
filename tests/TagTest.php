<?php

class TagTest extends TestCase
{
    /** @test */
    public function it_returns_an_array_of_tags()
    {
        $response = $this->json('GET', '/api/tags');

        $response->assertResponseOk();
        $response->shouldReturnJson();
    }
}
