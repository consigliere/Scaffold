<?php
/**
 * UserController.php
 * Created by @anonymoussc on 04/08/2019 11:35 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/10/19 2:36 AM
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\UserCreateFormRequest;
use App\Components\Scaffold\Http\Requests\UserUpdateFormRequest;
use App\Components\Scaffold\Services\UserService;
use App\Components\Signature\Exceptions\NotFoundHttpException;
use Illuminate\Http\Request;

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
        $this->type        = config('scaffold.api.users.type');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [];

        try {
            $response = $this->userService->profile($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function browse(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'header' => [
                'paging' => $request->header('X-Page-Paging') ?? config('scaffold.api.page_paging'),
            ],
        ];

        try {
            $response = $this->userService->browse($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param \App\Components\Scaffold\Http\Requests\UserCreateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(UserCreateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = ['input' => $request->all(),];

        try {
            $response = $this->userService->create($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response, 201);
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

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param                                                              $uuid
     * @param \App\Components\Scaffold\Http\Requests\UserUpdateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($uuid, UserUpdateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = ['input' => $request->all(),];

        try {
            $response = $this->userService->update($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
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

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api(null, 204);
    }

    /**
     * @param string                   $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedPrimaryRole($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data = ['input' => $request->all(),];

        try {
            $response = $this->userService->relatedPrimaryRole($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param string                   $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function primaryRole($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data = ['input' => $request->all(),];

        try {
            $response = $this->userService->userPrimaryRole($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param                          $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedAdditionalRoles($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data = ['input' => $request->all(),];

        try {
            $response = $this->userService->relatedAdditionalRoles($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param                          $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function additionalRoles($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data = ['input' => $request->all(),];

        try {
            $response = $this->userService->userAdditionalRoles($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param string                   $uuid
     * @param null                     $type
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userAdditionalRoles($uuid, $type = null, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = ['input' => $request->all(),];
        $option = ['type' => $type,];

        try {
            if ($type === 'add' || $type === 'remove' || $type === 'sync') {
                $response = $this->userService->operationAdditionalRoles($uuid, $data, $option);
            } else {
                throw new NotFoundHttpException("Resource requested cannot be found, type can be of 'sync', 'add', or 'remove'");
            }
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param string                   $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncAdditionalRoles($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = ['input' => $request->all(),];
        $option = ['type' => 'sync',];

        try {
            $response = $this->userService->operationAdditionalRoles($uuid, $data, $option);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param string                   $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAdditionalRoles($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = ['input' => $request->all(),];
        $option = ['type' => 'add',];

        try {
            $response = $this->userService->operationAdditionalRoles($uuid, $data, $option);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param string                   $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAdditionalRoles($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = ['input' => $request->all(),];
        $option = ['type' => 'remove',];

        try {
            $response = $this->userService->operationAdditionalRoles($uuid, $data, $option);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }
}
