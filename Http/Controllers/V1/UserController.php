<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/9/19 1:18 PM
 */

/**
 * UserController.php
 * Created by @anonymoussc on 04/08/2019 11:30 PM.
 */

namespace App\Components\Scaffold\Http\Controllers\V1;

use Illuminate\Http\Request;

class UserController extends \App\Components\Scaffold\Http\Controllers\UserController
{
    public function profile(Request $request)
    {
        return $request->user();
    }
}
