<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/21/19 3:10 AM
 */

namespace Tests\Feature;

use Tests\ScaffoldApiTestCase;

/**
 * Class GetAllRoleTest
 * @package Tests\Feature
 */
class GetAllRoleTest extends ScaffoldApiTestCase
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
