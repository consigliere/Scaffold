<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/7/19 5:55 PM
 */

/**
 * RoleCreateFormRequest.php
 * Created by @anonymoussc on 05/07/2019 4:34 PM.
 */

namespace App\Components\Scaffold\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleCreateFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'        => 'required',
            'displayName' => '',
        ];
    }
}