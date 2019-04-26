<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/27/19 4:07 AM
 */

/**
 * UserCreateFormRequest.php
 * Created by @anonymoussc on 04/20/2019 6:21 PM.
 */

namespace App\Components\Scaffold\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'roleId'          => '',
            'uuid'            => '',
            'username'        => 'required',
            'name'            => 'required',
            'email'           => 'required',
            'avatar'          => '',
            'emailVerifiedAt' => '',
            'password'        => 'required',
            'rememberToken'   => '',
            'settings'        => '',
        ];
    }
}