<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/22/19 1:48 AM
 */

/**
 * Repository.php
 * Created by @anonymoussc on 04/08/2019 11:50 PM.
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
     * @deprecated
     *
     * @param       $uuid
     * @param array $arg
     *
     * @return mixed
     */
    public function getIdFromUuid($uuid, array $arg = [])
    {
        $users = $this->getModel()::where('uuid', '=', $uuid)->orWhere('id', '=', $uuid)->get();

        $uid = [];
        $i   = 0;
        foreach ($users as $user) {
            $uid[$i] = $this->getModel()::where([
                ['id', $user->id],
                ['uuid', $uuid],
            ])->first();

            if (null === $uid[$i]) {
                unset($uid[$i]);
            } else {
                return $uid[$i]->id;
            }

            $i++;
        }
    }

    /**
     * @param       $uuid
     * @param array $arg
     *
     * @return mixed
     */
    public function getIdbyUuid($uuid, array $arg = [])
    {
        $users = $this->getModel()::where('uuid', '=', $uuid)->orWhere('id', '=', $uuid)->get();

        $uid = [];
        $i   = 0;
        foreach ($users as $user) {
            $uid[$i] = $this->getModel()::where([
                ['id', $user->id],
                ['uuid', $uuid],
            ])->first();

            if (null === $uid[$i]) {
                unset($uid[$i]);
            } else {
                return $uid[$i]->id;
            }

            $i++;
        }
    }
}