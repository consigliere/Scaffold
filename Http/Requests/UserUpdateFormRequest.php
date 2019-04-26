<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/27/19 4:07 AM
 */

/**
 * UserUpdateFormRequest.php
 * Created by @anonymoussc on 04/22/2019 1:24 PM.
 */

namespace App\Components\Scaffold\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'roleId'          => '',
            // 'uuid'            => '',
            'username'        => '',
            'name'            => '',
            'email'           => '',
            'avatar'          => '',
            'emailVerifiedAt' => '',
            'password'        => '',
            'rememberToken'   => '',
            'settings'        => '',
        ];
    }
}