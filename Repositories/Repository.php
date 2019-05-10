<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/10/19 7:20 PM
 */

/**
 * Repository.php
 * Created by @anonymoussc on 04/08/2019 11:50 PM.
 */

namespace App\Components\Scaffold\Repositories;

# @formatter:off
use App\Components\Signal\Shared\{ErrorLog,Signal};use App\Components\Signature\Repositories\SignatureRepository as BaseRepository;
# @formatter:on

/**
 * Class Repository
 * @package App\Components\Passerby\Repositories
 */
abstract class Repository extends BaseRepository
{
    use Signal, ErrorLog;

    /**
     * @param       $uuid
     * @param array $param
     *
     * @return mixed
     */
    public function getIdFromUuid($uuid, array $param = [])
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