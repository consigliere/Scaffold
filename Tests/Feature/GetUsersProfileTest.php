<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/21/19 3:10 AM
 */

namespace App\Components\Scaffold\Tests\Feature;

/**
 * Class GetUsersProfileTest
 * @package App\Components\Scaffold\Tests\Feature
 */
class GetUsersProfileTest extends \Tests\ScaffoldApiTestCase
{
    /**
     * @return void
     */
    public function testGetUsersProfile(): void
    {
        $this->init();

        $header = [
            'Accept' => 'application/vnd.api+json',
        ];

        $response = $this->get('/api/v1/users/profile', $header);

        $response->assertStatus(200);
    }
}
