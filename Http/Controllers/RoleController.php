<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/10/19 6:38 AM
 */

/**
 * RoleController.php
 * Created by @anonymoussc on 04/08/2019 11:36 PM.
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\RoleCreateFormRequest;
use App\Components\Scaffold\Http\Requests\RoleUpdateFormRequest;
use App\Components\Scaffold\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RoleController extends Controller
{
    public $type;

    public $roleService;

    public function __construct(RoleService $RoleService)
    {
        $this->roleService = $RoleService;
        $this->type        = 'roles';
    }

    public function browse(Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'header' => [
                'paging' => $request->header('Page-Paging') ?? Config::get('scaffold.api.page_paging'),
            ],
        ];
        $option = $this->getOption($request);
        $param  = $this->getParam($request, ['type' => $this->type]);

        try {
            $response = $this->roleService->browse($data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 200);
    }

    public function create(RoleCreateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'form' => $request->all(),
        ];
        $option = $this->getOption($request);
        $param  = $this->getParam($request, ['type' => $this->type]);

        try {
            $response = $this->roleService->create($data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 201);
    }

    public function read($uuid = null, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = [];
        $option = $this->getOption($request);
        $param  = $this->getParam($request, ['type' => $this->type]);

        try {
            $response = $this->roleService->read($uuid, $data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 200);
    }

    public function update($uuid, RoleUpdateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'form' => $request->all(),
        ];
        $option = $this->getOption($request);
        $param  = $this->getParam($request, ['type' => $this->type]);

        try {
            $response = $this->roleService->update($uuid, $data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 200);
    }

    public function delete($uuid, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->roleService->delete($uuid);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response(null, 204);
    }
}