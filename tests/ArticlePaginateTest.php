<?php

class ArticlePaginateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');
        $this->artisan('db:seed');

        $users = factory(\App\Models\User::class)->times(2)->create();

        $this->loggedInUser = $users[0];

        $this->user = $users[1];

        $this->headers = [
            'Authorization' => "Token {$this->loggedInUser->token}"
        ];
    }

    /** @test */
    public function it_returns_the_correct_articles_with_limit_and_offset()
    {
        $response = $this->json('GET', '/api/articles');

        $response->assertResponseOk();
        $response->seeJsonContains(['articlesCount' => 25]);

        // $this->assertCount(20, $this->getResponseData()['articles'], 'Expected articles to set default limit to 20');

        $response = $this->json('GET', '/api/articles?limit=10&offset=5');

        $response->assertResponseOk();
        $response->seeJsonContains(['articlesCount' => 10]);

        // $this->assertCount(10, $this->getResponseData()['articles'], 'Expected article limit of 10 when set');
    }
}
