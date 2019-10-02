<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 10/3/19 1:48 AM
 */

/**
 * ResponseUserGetLinksResource.php
 * Created by @anonymoussc on 08/23/2019 11:35 PM.
 */

namespace App\Components\Scaffold\Services\User\Shared;


/**
 * Trait ResponseUserGetLinksResource
 *
 * @package App\Components\Scaffold\Services\User\Shared
 */
trait ResponseUserGetLinksResource
{
    /**
     * @return array
     */
    private function getLinks(): array
    {
        return [
            'self' => $this->request->fullUrl(),
        ];
    }
}
