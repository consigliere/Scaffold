<?php
/**
 * RoleController.php
 * Created by @anonymoussc on 04/08/2019 11:36 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/17/19 11:29 AM
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\RoleCreateFormRequest;
use App\Components\Scaffold\Http\Requests\RoleUpdateFormRequest;
use App\Components\Scaffold\Services\RoleService;
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
        $data   = [
            'header' => [
                'paging' => $request->header('Page-Paging') ?? Config::get('scaffold.api.page_paging'),
            ],
        ];

        try {
            $response = $this->roleService->browse($data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
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
        $data   = [
            'input' => $request->all(),
        ];

        try {
            $response = $this->roleService->create($data);
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
        $data   = [];

        try {
            $response = $this->roleService->read($uuid, $data);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
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
        $data   = [
            'input' => $request->all(),
        ];

        try {
            $response = $this->roleService->update($uuid, $data);
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
            $this->roleService->delete($uuid);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error, 'uuid' => $this->euuid]);

            return $this->response($this->getErrorResponse($this->euuid, $error), httpStatusCode($error));
        }

        return $this->response(null, 204);
    }
}