<?php

class ArticleCreateTest extends TestCase
{
    /** @test */
    public function it_returns_the_article_on_successfully_creating_a_new_article()
    {
        $data = [
            'article' => [
                'title' => 'test title',
                'description' => 'test description',
                'body' => 'test body with random text',
                'tagList' => ['test', 'coding'],
            ]
        ];

        $response = $this->json('POST', '/api/articles', $data, $this->headers);

        $response->assertResponseStatus(201);
        $response->seeJsonStructure([
                'article' => [
                    'slug',
                    'title',
                    'description',
                    'body',
                    'tagList',
                    'favorited',
                    'favoritesCount',
                    'author' => [
                        'username',
                        'bio',
                        'image',
                        'following',
                    ]
                ]
        ]);
        $response->seeJsonContains(['title' => 'test title']);
        $response->seeJsonContains(['username' => $this->loggedInUser->username]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_when_creating_a_new_article_with_invalid_inputs()
    {
        $data = [
            'article' => [
                'title' => '',
                'description' => '',
                'body' => '',
            ]
        ];

        $response = $this->json('POST', '/api/articles', $data, $this->headers);

        // $response->assertResponseStatus(422);
        $response->seeJsonEquals([
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
        $data = [
            'article' => [
                'title' => 'test title',
                'description' => 'test description',
                'body' => 'test body with random text',
                'tags' => ['test', 'coding'],
            ]
        ];

        $response = $this->json('POST', '/api/articles', $data);

        $response->assertResponseStatus(401);
    }
}
