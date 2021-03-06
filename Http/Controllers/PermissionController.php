<?php
/**
 * PermissionController.php
 * Created by @anonymoussc on 04/08/2019 11:37 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/10/19 2:36 AM
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\PermissionCreateFormRequest;
use App\Components\Scaffold\Http\Requests\PermissionUpdateFormRequest;
use App\Components\Scaffold\Services\PermissionService;
use Illuminate\Http\Request;

/**
 * Class PermissionController
 * @package App\Components\Scaffold\Http\Controllers
 */
class PermissionController extends Controller
{
    /**
     * @var \App\Components\Scaffold\Services\PermissionService
     */
    private $permissionService;

    /**
     * PermissionController constructor.
     *
     * @param \App\Components\Scaffold\Services\PermissionService $PermissionService
     */
    public function __construct(PermissionService $PermissionService)
    {
        $this->permissionService = $PermissionService;
        $this->euuid             = randomUuid();
        $this->type              = config('scaffold.api.permissions.type');
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
                'paging' => $request->header('X-Page-Paging') ?? config('scaffold.api.page_paging'),
            ],
        ];

        try {
            $response = $this->permissionService->browse($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param \App\Components\Scaffold\Http\Requests\PermissionCreateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(PermissionCreateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'input' => $request->all(),
        ];

        try {
            $response = $this->permissionService->create($data);
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
        $data   = [];

        try {
            $response = $this->permissionService->read($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api($response);
    }

    /**
     * @param                                                                    $uuid
     * @param \App\Components\Scaffold\Http\Requests\PermissionUpdateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($uuid, PermissionUpdateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'input' => $request->all(),
        ];

        try {
            $response = $this->permissionService->update($uuid, $data);
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
            $this->permissionService->delete($uuid);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return response()->ApiError($this->euuid, $error);
        }

        return response()->Api(null, 204);
    }
}