<?php

class ProfileTest extends TestCase
{
    /** @test */
    public function it_returns_a_valid_profile()
    {
        $response = $this->json('GET', "/api/profiles/{$this->user->username}");

        $response->assertResponseOk();
        $response->seeJsonStructure([
            'profile' => [
                'username',
                'bio',
                'image',
                'following',
            ]
        ]);

        $response->seeJsonContains(['bio' => $this->user->bio]);
        $response->seeJsonContains(['image' => $this->user->image]);
        $response->seeJsonContains(['username' => $this->user->username]);
        $response->seeJsonContains(['following' => false]);
    }

    /** @test */
    public function it_returns_a_not_found_error_on_invalid_profile()
    {
        $response = $this->json('GET', '/api/profiles/somerandomusername');

        $response->assertResponseStatus(404);
    }

    /** @test */
    public function it_returns_the_profile_following_property_accordingly_when_followed_and_unfollowed()
    {
        $response = $this->json('POST', "/api/profiles/{$this->user->username}/follow", [], $this->headers);

        $response->seeJsonStructure([
            'profile' => [
                'username',
                'bio',
                'image',
                'following',
            ]
        ]);

        $response->seeJsonContains(['bio' => $this->user->bio]);
        $response->seeJsonContains(['image' => $this->user->image]);
        $response->seeJsonContains(['username' => $this->user->username]);
        $response->seeJsonContains(['following' => true]);

        $this->assertTrue($this->loggedInUser->isFollowing($this->user), 'Failed to follow user');

        $response = $this->json('DELETE', "/api/profiles/{$this->user->username}/follow", [], $this->headers);

        $response->assertResponseOk();
        $response->seeJsonContains(['following' => false]);

        $this->assertFalse($this->loggedInUser->isFollowing($this->user), 'Failed to unfollow user');
    }

    /** @test */
    public function it_returns_a_not_found_error_when_trying_to_follow_and_unfollow_an_invalid_user()
    {
        $response = $this->json('POST', "/api/profiles/somerandomusername/follow", [], $this->headers);

        $response->assertResponseStatus(404);

        $response = $this->JSON('DELETE', "/api/profiles/somerandomusername/follow", [], $this->headers);

        $response->assertResponseStatus(404);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_trying_to_follow_or_unfollow_without_logging_in()
    {
        $response = $this->json('POST', "/api/profiles/{$this->user->username}/follow");

        $response->assertResponseStatus(401);

        $response = $this->json('DELETE', "/api/profiles/{$this->user->username}/follow");

        $response->assertResponseStatus(401);
    }
}
