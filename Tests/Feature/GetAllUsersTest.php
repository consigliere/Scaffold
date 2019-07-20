<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/21/19 3:01 AM
 */

namespace App\Components\Scaffold\Tests\Feature;

/**
 * Class GetAllUsersTest
 * @package App\Components\Scaffold\Tests\Feature
 */
class GetAllUsersTest extends \Tests\ApiTestCase
{
    /**
     * @return void
     */
    public function testGetAllUsers(): void
    {
        $this->init();

        $header = [
            'Accept'        => 'application/vnd.api+json',
            'X-Page-Paging' => 5,
        ];

        $response = $this->get('/api/v1/users', $header);

        $response->assertStatus(200);
    }
}
