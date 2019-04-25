<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/25/19 6:59 PM
 */

/**
 * UserController.php
 * Created by @anonymoussc on 04/08/2019 11:35 PM.
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\{
    UserCreateFormRequest, UserUpdateFormRequest
};
use App\Components\Scaffold\Services\UserService;

class UserController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function browse()
    {

    }

    public function create(UserCreateFormRequest $request)
    {
        try {
            $data   = $request->all();
            $option = [];

            $param['self']['link'] = $request->fullUrl();

            $response = $this->userService->create($data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 201);
    }

    public function read()
    {
    }

    public function update($id, UserUpdateFormRequest $request)
    {

    }

    public function delete()
    {
    }
}
