<?php

class LoginTest extends TestCase
{
    /** @test */
    public function it_returns_a_user_with_valid_token_on_valid_login()
    {
        $data = [
            'user' => [
                'email' => $this->user->email,
                'password' => 'password',
            ]
        ];

        $response = $this->json('POST', '/api/users/login', $data);

        $response->assertResponseOk();
        $response->seeJsonStructure([
            'user' => [
                'bio',
                'email',
                'image',
                'token',
                'username',
            ]
        ]);

        $response->seeJsonContains(['bio' => $this->user->bio]);
        $response->seeJsonContains(['email' => $this->user->email]);
        $response->seeJsonContains(['image' => $this->user->image]);
        $response->seeJsonContains(['username' => $this->user->username]);

        $responseData = $this->getResponseData();

        $this->assertArrayHasKey('token', $responseData['user'], 'Token not found');

        $this->assertTrue(
            (count(explode('.', $responseData['user']['token'])) === 3),
            'Failed to validate token'
        );
    }

    /** @test */
    public function it_returns_field_required_validation_errors_on_invalid_login()
    {
        $data = [];

        $response = $this->json('POST', '/api/users/login', $data);

        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
                'errors' => [
                    'email' => ['field is required.'],
                    'password' => ['field is required.'],
                ]
            ]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_on_invalid_login()
    {
        $data = [
            'user' => [
                'email' => 'invalid email',
                'password' => 'password',
            ]
        ];

        $response = $this->json('POST', '/api/users/login', $data);

        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
                'errors' => [
                    'email' => ['must be a valid email address.'],
                ]
            ]);
    }
}
