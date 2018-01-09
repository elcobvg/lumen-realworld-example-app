<?php

class RegistrationTest extends TestCase
{
    /** @test */
    public function it_returns_user_with_token_on_valid_registration()
    {
        $user = factory('App\Models\User')->make();
        $data = [
            'user' => [
                'username' => $user->username,
                'email' => $user->email,
                'password' => $user->password,
            ]
        ];

        $response = $this->json('POST', '/api/users', $data);

        $response->assertResponseStatus(201);
        $response->seeJsonStructure([
            'user' => [
                'email',
                'username',
            ]
        ]);

        $this->assertArrayHasKey('token', $this->getResponseData()['user'], 'Token not found');
    }

    /** @test */
    public function it_returns_field_required_validation_errors_on_invalid_registration()
    {
        $data = [];

        $response = $this->json('POST', '/api/users', $data);

        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
                'errors' => [
                    'username' => ['field is required.'],
                    'email' => ['field is required.'],
                    'password' => ['field is required.'],
                ]
            ]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_on_invalid_registration()
    {
        $data = [
            'user' => [
                'username' => 'invalid username',
                'email' => 'invalid email',
                'password' => '1',
            ]
        ];

        $response = $this->json('POST', '/api/users', $data);

        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
                'errors' => [
                    'username' => ['may only contain letters and numbers.'],
                    'email' => ['must be a valid email address.'],
                    'password' => ['must be at least 8 characters.'],
                ]
            ]);
    }

    /** @test */
    public function it_returns_username_and_email_taken_validation_errors_when_using_duplicate_values_on_registration()
    {
        $data = [
            'user' => [
                'username' => $this->user->username,
                'email' => $this->user->email,
                'password' => $this->user->password,
            ]
        ];

        $response = $this->json('POST', '/api/users', $data);

        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
                'errors' => [
                    'username' => ['has already been taken.'],
                    'email' => ['has already been taken.'],
                ]
            ]);
    }
}
