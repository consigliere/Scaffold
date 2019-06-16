<?php
/**
 * PermissionUpdateFormRequest.php
 * Created by @anonymoussc on 05/12/2019 9:01 AM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/17/19 12:08 AM
 */

namespace App\Components\Scaffold\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PermissionUpdateFormRequest
 * @package App\Components\Scaffold\Http\Requests
 */
class PermissionUpdateFormRequest extends FormRequest
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
            'key'    => '',
            'entity' => '',
        ];
    }
}