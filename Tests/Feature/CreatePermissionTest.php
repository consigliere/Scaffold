<?php
/**
 * CreatePermissionTest.php
 * Created by @anonymoussc on 07/21/2019 2:44 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/21/19 3:10 AM
 */

namespace App\Components\Scaffold\Tests\Feature;

/**
 * Class CreatePermissionTest
 * @package App\Components\Scaffold\Tests\Feature
 */
class CreatePermissionTest extends \Tests\ScaffoldApiTestCase
{
    /**
     * @return void
     */
    public function testCreateRoles(): void
    {
        $this->init();

        $header = [
            'Accept'       => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ];

        $response = $this->json('POST', '/api/v1/permissions', [
            "key"    => "can_do" . mt_rand(),
            "entity" => "something" . mt_rand(),
        ], $header);

        // $response->dumpHeaders();

        // $response->dump();

        $response->assertStatus(201);
    }
}
