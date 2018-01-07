<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ArticleCreateTest extends TestCase
{
    // use DatabaseMigrations;

    /** @test */
    public function it_returns_the_article_on_successfully_creating_a_new_article()
    {
        $data = [
            'article' => [
                'title' => 'test title',
                'description' => 'test description',
                'body' => 'test body with random text',
                'tags' => ['test', 'coding'],
            ]
        ];

        $response = $this->json('POST', '/api/articles', $data);

        $response->assertResponseStatus(200);
        $response->seeJsonContains([
                'article' => [
                    'slug' => 'test-title',
                    'title' => 'test title',
                    'description' => 'test description',
                    'body' => 'test body with random text',
                    'tagList' => ['test', 'coding'],
                    'favorited' => false,
                    'favoritesCount' => 0,
                    'author' => [
                        'username' => $this->loggedInUser->username,
                        'bio' => $this->loggedInUser->bio,
                        'image' => $this->loggedInUser->image,
                        'following' => false,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_when_creating_a_new_article_with_invalid_inputs()
    {
        $data = [
            'article' => [
                'title' => '',
                'description' => '',
            ]
        ];

        $response = $this->json('POST', '/api/articles', $data);

        $response->assertResponseStatus(422);
        $response->assertJson([
                'errors' => [
                    'title' => ['field is required.'],
                    'description' => ['field is required.'],
                    'body' => ['field is required.'],
                ]
            ]);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_trying_to_add_article_without_logging_in()
    {
        $response = $this->json('POST', '/api/articles', []);

        $response->assertStatus(401);
    }
}
