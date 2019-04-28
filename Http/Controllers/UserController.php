<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/29/19 3:19 AM
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
use Illuminate\Http\Request;

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

    public function update($uuid, UserUpdateFormRequest $request)
    {
        try {
            $data   = $request->all();
            $option = [];

            $param['self']['link'] = $request->fullUrl();

            $response = $this->userService->update($uuid, $data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 200);
    }

    public function delete($uuid, Request $request)
    {
        try {
            $response = $this->userService->delete($uuid);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response(null, 204);
    }
}
