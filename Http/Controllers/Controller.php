<?php
/**
 * Controller.php
 * Created by @anonymoussc on 04/08/2019 11:29 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/19/19 11:50 PM
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Signal\Shared\ErrorLog;
use App\Components\Signal\Shared\Signal;
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

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $euuid;
}