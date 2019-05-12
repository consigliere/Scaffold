<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/7/19 5:55 PM
 */

/**
 * RoleUpdateFormRequest.php
 * Created by @anonymoussc on 05/07/2019 4:38 PM.
 */

namespace App\Components\Scaffold\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'        => '',
            'displayName' => '',
        ];
    }
}