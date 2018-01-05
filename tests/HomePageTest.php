<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class HomePageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomePage()
    {
        $this->get('/');

        $this->assertEquals(
            file_get_contents(__DIR__ . '/../readme.md'), $this->response->getContent()
        );
    }
}
