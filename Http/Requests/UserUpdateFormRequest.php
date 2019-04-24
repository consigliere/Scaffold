<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/25/19 5:26 AM
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
            'roleId'   => '',
            //'uuid'              => '',
            'username' => 'required',
            'name'     => 'required',
            'email'    => 'required',
            //'avatar'            => '',
            //'emailVerifiedAt' => '',
            'password' => 'required',
            //'rememberToken'    => '',
            'settings' => '',
        ];
    }
}