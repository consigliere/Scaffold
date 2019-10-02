<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 10/3/19 1:48 AM
 */

/**
 * ResponseUserGetMetaResource.php
 * Created by @anonymoussc on 08/23/2019 11:39 PM.
 */

namespace App\Components\Scaffold\Services\User\Shared;


/**
 * Trait ResponseUserGetMetaResource
 *
 * @package App\Components\Scaffold\Services\User\Shared
 */
trait ResponseUserGetMetaResource
{
    /**
     * @return array
     */
    private function getMetas(): array
    {
        $year = date('Y');
        $name = config('app.name') ?? config('scaffold.name') ?? 'app';

        return [
            'copyright' => "copyrightⒸ $year $name",
            'author'    => config('scaffold.api.users.authors'),
        ];
    }
}
