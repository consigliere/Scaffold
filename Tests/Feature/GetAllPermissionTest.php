<?php
/**
 * GetAllPermissionTest.php
 * Created by @anonymoussc on 07/21/2019 2:41 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/21/19 2:54 AM
 */

namespace Tests\Feature;

use Tests\ApiTestCase;

/**
 * Class GetAllRoleTest
 * @package Tests\Feature
 */
class GetAllPermissionTest extends ApiTestCase
{
    /**
     * @return void
     */
    public function testGetAllPermission(): void
    {
        $this->init();

        $header = [
            'Accept'        => 'application/vnd.api+json',
            'X-Page-Paging' => 5,
        ];

        $response = $this->get('/api/v1/permissions', $header);

        $response->assertStatus(200);
    }
}
