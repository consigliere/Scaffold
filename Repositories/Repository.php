<?php
/**
 * Repository.php
 * Created by @anonymoussc on 04/08/2019 11:50 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/15/19 11:21 PM
 */

namespace App\Components\Scaffold\Repositories;

use App\Components\Signal\Shared\ErrorLog;
use App\Components\Signal\Shared\Signal;
use App\Components\Signature\Repositories\SignatureRepository as BaseRepository;

/**
 * Class Repository
 * @package App\Components\Passerby\Repositories
 */
abstract class Repository extends BaseRepository
{
    use Signal, ErrorLog;

    /**
     * @param $idOrUuid
     *
     * @return mixed
     */
    public function getIdbyUuid($idOrUuid, array $arg = [])
    {
        if (substr_count($idOrUuid, '-') >= 1) {
            $role = $this->getModel()::where('uuid', '=', $idOrUuid)->first();

            if (null === $role) {
                return $role;
            }

            return $role->id;
        }

        return $idOrUuid;
    }
}