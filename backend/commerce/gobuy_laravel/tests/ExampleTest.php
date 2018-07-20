<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    protected $postData = [
            'name' => 'example',
            'email' => 'example@example.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234'
    ];

    public function testBasicExample()
    {
        // $this->visit('/')
        //      ->see('Laravel 5');
         $this->seeInDatabase('users', ['email' => 'vip131430@yahoo.com.tw']);
    }
}
