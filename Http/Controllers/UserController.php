<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/1/19 6:02 AM
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
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function browse(Request $request)
    {
        $data   = [];
        $option = [
            'header' => [
                'paging' => $request->header('Page-Paging') ?? Config::get('scaffold.api.page_paging'),
            ],
        ];
        $param  = [
            'link' => [
                'fullUrl' => $request->fullUrl(),
                'url'     => $request->url(),
            ],
        ];

        try {
            $response = $this->userService->browse($data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 200);
    }

    public function create(UserCreateFormRequest $request)
    {
        $data   = $request->all();
        $option = [];
        $param  = [
            'link' => [
                'fullUrl' => $request->fullUrl(),
            ],
        ];

        try {
            $response = $this->userService->create($data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 201);
    }

    public function read($uuid = null, $relationship = null, Request $request)
    {

    }

    public function update($uuid, UserUpdateFormRequest $request)
    {
        $data   = $request->all();
        $option = [];
        $param  = [
            'link' => [
                'fullUrl' => $request->fullUrl(),
            ],
        ];

        try {
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
