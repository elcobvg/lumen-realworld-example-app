<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    protected $baseUrl = 'http://realworld.test:8080';

    protected $loggedInUser;

    protected $user;

    protected $headers;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();

        $users = factory(\App\Models\User::class)->times(2)->create();

        $this->loggedInUser = $users[0];

        $this->user = $users[1];

        $this->headers = [
            'Authorization' => "Token {$this->loggedInUser->token}"
        ];
    }
}
