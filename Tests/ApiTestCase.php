<?php
/**
 * ApiTestCase.php
 * Created by @anonymoussc on 07/20/2019 5:40 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/21/19 3:06 AM
 */

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

/**
 * Class ApiTestCase
 * @package Tests
 */
abstract class ApiTestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    public function init()
    {
        $scopes = \App\Components\Scaffold\Entities\Permission::where('id', '>', 0)->pluck('key');

        Passport::actingAs(
            factory(\Api\User\Entities\User::class)->create(['role_id' => 2, 'uuid' => randomUuid(), 'username' => 'test' . mt_rand()]),
            $scopes->toArray()
        );
    }
}
