<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/21/19 2:56 AM
 */

namespace Tests\Feature;

use Tests\ApiTestCase;

/**
 * Class GetAllRoleTest
 * @package Tests\Feature
 */
class GetAllRoleTest extends ApiTestCase
{
    /**
     * @return void
     */
    public function testGetAllRole(): void
    {
        $this->init();

        $header = [
            'Accept'        => 'application/vnd.api+json',
            'X-Page-Paging' => 5,
        ];

        $response = $this->get('/api/v1/roles', $header);

        $response->assertStatus(200);
    }
}
