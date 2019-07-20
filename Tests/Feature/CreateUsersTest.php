<?php
/**
 * CreateUsersTest.php
 * Created by @anonymoussc on 07/20/2019 10:40 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/21/19 3:10 AM
 */

namespace App\Components\Scaffold\Tests\Feature;

/**
 * Class CreateUsersTest
 * @package App\Components\Scaffold\Tests\Feature
 */
class CreateUsersTest extends \Tests\ScaffoldApiTestCase
{
    /**
     * @return void
     */
    public function testCreateUsers(): void
    {
        $this->init();

        $header = [
            'Accept'       => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ];

        $response = $this->json('POST', '/api/v1/users', [
            "roleId"          => "",
            "username"        => "dummytests" . mt_rand(),
            "name"            => "Dummy" . mt_rand() . " Tests",
            "email"           => "dummytests" . mt_rand() . "@email.com",
            "avatar"          => "",
            "emailVerifiedAt" => "",
            "password"        => "dummytests" . mt_rand(),
            "rememberToken"   => "",
            "settings"        => "",
        ], $header);

        // $response->dumpHeaders();

        // $response->dump();

        $response->assertStatus(201);
    }
}
