<?php
/**
 * UserController.php
 * Created by @anonymoussc on 04/08/2019 11:35 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/15/19 6:08 PM
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
     * @var \App\Components\Scaffold\Services\UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param \App\Components\Scaffold\Services\UserService $UserService
     */
    public function __construct(UserService $UserService)
    {
        $this->userService = $UserService;
        $this->euuid       = randomUuid();
        $this->type        = Config::get('scaffold.api.users.type');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = [];

        try {
            $response = $this->userService->profile($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
        }

        return $this->response($response);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function browse(Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'header' => [
                'paging' => $request->header('Page-Paging') ?? Config::get('scaffold.api.page_paging'),
            ],
        ];

        try {
            $response = $this->userService->browse($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
        }

        return $this->response($response);
    }

    /**
     * @param \App\Components\Scaffold\Http\Requests\UserCreateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(UserCreateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'form' => $request->all(),
        ];

        try {
            $response = $this->userService->create($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
        }

        return $this->response($response, 201);
    }

    /**
     * @param null                     $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read($uuid = null, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->userService->read($uuid);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid,]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
        }

        return $this->response($response);
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
            'form' => $request->all(),
        ];

        try {
            $response = $this->userService->update($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
        }

        return $this->response($response);
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
            $this->userService->delete($uuid);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
        }

        return $this->response(null, 204);
    }
}
