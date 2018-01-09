<?php

class ArticleReadTest extends TestCase
{
    /** @test */
    public function it_returns_the_articles_and_correct_total_article_count()
    {
        $articles = $this->user->articles()->saveMany(factory(\App\Models\Article::class)->times(2)->make());

        $response = $this->json('GET', '/api/articles');

        $response->assertResponseOk();
        $response->seeJsonStructure([
                'articles' => [
                    [
                        'slug',
                        'title',
                        'description',
                        'body',
                        'tagList',
                        'createdAt',
                        'updatedAt',
                        'favorited',
                        'favoritesCount',
                        'author' => [
                            'username',
                            'bio',
                            'image',
                            'following',
                        ]
                    ],
                    [
                        'slug',
                        'title',
                    ]
                ],
                'articlesCount'
        ]);

        // $response->seeJsonContains(['articlesCount' => 2]);
        $response->seeJsonContains(['slug' => $articles[0]->slug]);
        $response->seeJsonContains(['slug' => $articles[1]->slug]);
        $response->seeJsonContains(['createdAt' => $articles[1]->created_at->toAtomString()]);
        $response->seeJsonContains(['username' => $this->user->username]);
    }

    /** @test */
    public function it_returns_the_article_by_slug_if_valid_and_not_found_error_if_invalid()
    {
        $article = $this->user->articles()->save(factory(\App\Models\Article::class)->make());

        $response = $this->json('GET', '/api/articles');

        $response->assertResponseOk();

        $response->seeJsonContains(['slug' => $article->slug]);
        $response->seeJsonContains(['title' => $article->title]);
        $response->seeJsonContains(['createdAt' => $article->created_at->toAtomString()]);
        $response->seeJsonContains(['username' => $this->user->username]);

        $response = $this->json('GET', '/api/articles/randominvalidslug');

        $response->assertResponseStatus(404);
    }
}
