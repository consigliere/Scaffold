<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/12/19 7:27 AM
 */

/**
 * RoleCreateFormRequest.php
 * Created by @anonymoussc on 05/07/2019 4:34 PM.
 */

namespace App\Components\Scaffold\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RoleCreateFormRequest
 * @package App\Components\Scaffold\Http\Requests
 */
class RoleCreateFormRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'displayName' => '',
        ];
    }
}