<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/15/19 9:21 PM
 */

/**
 * Service.php
 * Created by @anonymoussc on 01/03/2019 5:55 PM.
 */

namespace App\Components\Scaffold\Services;

use App\Components\Signal\Shared\ErrorLog;
use App\Components\Signal\Shared\Signal;
use App\Components\Signature\Services\SignatureService as BaseService;

/**
 * Class Service
 * @package App\Components\Passerby\Services
 */
class Service extends BaseService
{
    use Signal, ErrorLog;
}
