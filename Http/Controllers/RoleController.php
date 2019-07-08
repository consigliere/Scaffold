<?php
/**
 * RoleController.php
 * Created by @anonymoussc on 04/08/2019 11:36 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/8/19 8:18 PM
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\RoleCreateFormRequest;
use App\Components\Scaffold\Http\Requests\RoleUpdateFormRequest;
use App\Components\Scaffold\Services\RoleService;
use App\Components\Signature\Exceptions\UnauthorizedHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Class RoleController
 * @package App\Components\Scaffold\Http\Controllers
 */
class RoleController extends Controller
{
    /**
     * @var \App\Components\Scaffold\Services\RoleService
     */
    private $roleService;

    /**
     * RoleController constructor.
     *
     * @param \App\Components\Scaffold\Services\RoleService $RoleService
     */
    public function __construct(RoleService $RoleService)
    {
        $this->roleService = $RoleService;
        $this->euuid       = randomUuid();
        $this->type        = Config::get('scaffold.api.roles.type');
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
                'paging' => $request->header('Page-Paging') ?? Config::get('scaffold.api.page_paging'),
            ],
        ];

        try {
            if (!$request->user()->tokenCan('browse_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->browse($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return $this->response($response);
    }

    /**
     * @param \App\Components\Scaffold\Http\Requests\RoleCreateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(RoleCreateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'input' => $request->all(),
        ];

        try {
            if (!$request->user()->tokenCan('add_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->create($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
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
        $data = [];

        try {
            if (!$request->user()->tokenCan('read_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->read($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return $this->response($response);
    }

    /**
     * @param                                                              $uuid
     * @param \App\Components\Scaffold\Http\Requests\RoleUpdateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($uuid, RoleUpdateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'input' => $request->all(),
        ];

        try {
            if (!$request->user()->tokenCan('edit_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->update($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
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
            if (!$request->user()->tokenCan('delete_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $this->roleService->delete($uuid);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return $this->response(null, 204);
    }

    /**
     * @param                          $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function relatedPermissions($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data = ['input' => $request->all(),];

        try {
            if (!$request->user()->tokenCan('read_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->relatedPermissions($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return $this->response($response);
    }

    /**
     * @param                          $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data = ['input' => $request->all(),];

        try {
            if (!$request->user()->tokenCan('read_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->rolePermissions($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return $this->response($response);
    }

    /**
     * @param                          $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncPermissions($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = ['input' => $request->all(),];
        $option = ['type' => 'sync',];

        try {
            if (!$request->user()->tokenCan('edit_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->permissionAction($uuid, $data, $option);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return $this->response($response);
    }

    /**
     * @param                          $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPermissions($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = ['input' => $request->all(),];
        $option = ['type' => 'add',];

        try {
            if (!$request->user()->tokenCan('edit_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->permissionAction($uuid, $data, $option);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return $this->response($response);
    }

    /**
     * @param string                   $uuid
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removePermissions($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = ['input' => $request->all(),];
        $option = ['type' => 'remove',];

        try {
            if (!$request->user()->tokenCan('edit_roles')) {
                throw new UnauthorizedHttpException('Unauthorized to access resource');
            }

            $response = $this->roleService->permissionAction($uuid, $data, $option);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return $this->response($response);
    }
}