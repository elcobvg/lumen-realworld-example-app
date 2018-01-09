<?php

class ArticleDeleteTest extends TestCase
{
    /** @test */
    public function it_returns_a_200_success_response_on_successfully_removing_the_article()
    {
        $article = $this->loggedInUser->articles()->save(factory(\App\Models\Article::class)->make());

        $response = $this->json('DELETE', "/api/articles/{$article->slug}", [], $this->headers);

        $response->assertResponseStatus(204);

        $response = $this->json('GET', "/api/articles/{$article->slug}");

        $response->assertResponseStatus(404);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_trying_to_remove_article_without_logging_in()
    {
        $article = $this->loggedInUser->articles()->save(factory(\App\Models\Article::class)->make());

        $response = $this->json('DELETE', "/api/articles/{$article->slug}");

        $response->assertResponseStatus(401);
    }

    /** @test */
    public function it_returns_a_forbidden_error_when_trying_to_remove_articles_by_others()
    {
        $article = $this->user->articles()->save(factory(\App\Models\Article::class)->make());

        $response = $this->json('DELETE', "/api/articles/{$article->slug}", [], $this->headers);

        $response->assertResponseStatus(403);
    }
}
