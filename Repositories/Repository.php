<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:40 PM
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
}