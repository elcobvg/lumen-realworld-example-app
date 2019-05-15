<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    // protected $baseUrl = 'http://realworld.test:8080';

    protected $loggedInUser;

    protected $user;

    protected $headers;

    protected static $migrationsRun = false;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }


    public function setUp(): void
    {
        parent::setUp();

        if (!static::$migrationsRun) {
            $this->artisan('migrate:refresh');
            $this->artisan('db:seed');
            static::$migrationsRun = true;
        }

        $this->beforeApplicationDestroyed(function () {
            // $this->artisan('migrate:rollback');
        });

        $users = factory(\App\Models\User::class)->times(2)->create();

        $this->loggedInUser = $users[0];

        $this->user = $users[1];

        $this->headers = [
            'Authorization' => "Token {$this->loggedInUser->token}"
        ];
    }

    /**
     * Get the JSON data from the response and return as assoc. array
     *
     * @return array
     */
    public function getResponseData()
    {
        return json_decode(json_encode($this->response->getData()), true);
    }
}
