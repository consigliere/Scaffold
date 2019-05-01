<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/2/19 3:19 AM
 */

/**
 * UserController.php
 * Created by @anonymoussc on 04/08/2019 11:35 PM.
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\UserCreateFormRequest;
use App\Components\Scaffold\Http\Requests\UserUpdateFormRequest;
use App\Components\Scaffold\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Class UserController
 * @package App\Components\Scaffold\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @var string
     */
    public $type;
    /**
     * @var \App\Components\Scaffold\Services\UserService
     */
    public $userService;

    /**
     * UserController constructor.
     *
     * @param \App\Components\Scaffold\Services\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->type        = 'users';
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function browse(Request $request): \Illuminate\Http\JsonResponse
    {
        $data          = [
            'header' => [
                'paging' => $request->header('Page-Paging') ?? Config::get('scaffold.api.page_paging'),
            ],
        ];
        $opt['option'] = [];
        $par['param']  = [
            'type' => $this->type,
            'link' => [
                'fullUrl' => $request->fullUrl(),
                'url'     => $request->url(),
            ],
        ];

        try {
            $response = $this->userService->browse($data, $par, $opt);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 200);
    }

    /**
     * @param \App\Components\Scaffold\Http\Requests\UserCreateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(UserCreateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'input' => $request->all(),
        ];
        $option = [];
        $param  = [
            'type' => $this->type,
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

    /**
     * @param null                     $uuid
     * @param null                     $relationship
     * @param \Illuminate\Http\Request $request
     */
    public function read($uuid = null, $relationship = null, Request $request)
    {

    }

    /**
     * @param                                                              $uuid
     * @param \App\Components\Scaffold\Http\Requests\UserUpdateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($uuid, UserUpdateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'input' => $request->all(),
        ];
        $option = [];
        $param  = [
            'type' => $this->type,
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

    /**
     * @param                          $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($uuid, Request $request): \Illuminate\Http\JsonResponse
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
