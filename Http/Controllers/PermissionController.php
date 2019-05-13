<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/13/19 7:26 AM
 */

/**
 * PermissionController.php
 * Created by @anonymoussc on 04/08/2019 11:37 PM.
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\PermissionCreateFormRequest;
use App\Components\Scaffold\Http\Requests\PermissionUpdateFormRequest;
use App\Components\Scaffold\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Class PermissionController
 * @package App\Components\Scaffold\Http\Controllers
 */
class PermissionController extends Controller
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var \App\Components\Scaffold\Services\PermissionService
     */
    public $permissionService;

    /**
     * PermissionController constructor.
     *
     * @param \App\Components\Scaffold\Services\PermissionService $PermissionService
     */
    public function __construct(PermissionService $PermissionService)
    {
        $this->permissionService = $PermissionService;
        $this->type              = 'permissions';
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
        $option = $this->getOption($request);
        $param  = $this->getParam($request, ['type' => $this->type]);

        try {
            $response = $this->permissionService->browse($data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 200);
    }

    /**
     * @param \App\Components\Scaffold\Http\Requests\PermissionCreateFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(PermissionCreateFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $data   = [
            'form' => $request->all(),
        ];
        $option = $this->getOption($request);
        $param  = $this->getParam($request, ['type' => $this->type]);

        try {
            $response = $this->permissionService->create($data, $option, $param);
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read($uuid = null, Request $request): \Illuminate\Http\JsonResponse
    {
        $data   = [];
        $option = $this->getOption($request);
        $param  = $this->getParam($request, ['type' => $this->type]);

        try {
            $response = $this->permissionService->read($uuid, $data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response($response, 200);
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
            'form' => $request->all(),
        ];
        $option = $this->getOption($request);
        $param  = $this->getParam($request, ['type' => $this->type]);

        try {
            $response = $this->permissionService->update($uuid, $data, $option, $param);
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
            $this->permissionService->delete($uuid);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        return $this->response(null, 204);
    }
}