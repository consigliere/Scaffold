<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/25/19 5:26 AM
 */

/**
 * Controller.php
 * Created by @anonymoussc on 04/08/2019 11:29 PM.
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Signal\Shared\{
    ErrorLog, Signal
};
use App\Components\Signature\Http\Controllers\SignatureController as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class Controller
 * @package App\Components\Passerby\Http\Controllers
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Signal, ErrorLog;
}