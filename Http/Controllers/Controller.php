<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/4/19 12:57 AM
 */

/**
 * Controller.php
 * Created by @anonymoussc on 04/08/2019 11:29 PM.
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Signal\Shared\{
    ErrorLog, Signal
};
use App\Components\Signature\Http\Controllers\SignatureController as BaseController;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use JsonSerializable;

/**
 * Class Controller
 * @package App\Components\Passerby\Http\Controllers
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Signal, ErrorLog;

    /**
     * Create a json response
     *
     * @param       $data
     * @param int   $statusCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($data, $statusCode = 200, array $headers = ['Content-Type' => 'application/vnd.api+json',]): JsonResponse
    {
        if ($data instanceof Arrayable && !$data instanceof JsonSerializable) {
            $data = $data->toArray();
        }

        return new JsonResponse($data, $statusCode, $headers);
    }

    /**
     * @param       $req
     * @param array $param
     *
     * @return array
     */
    protected function getOption($req, array $param = []): array
    {
        $option = [
            'api' => [
                'hasLink' => true,
                'hasMeta' => true,
            ],
        ];

        return Arr::dot($option);
    }

    /**
     * @param       $req
     * @param array $param
     *
     * @return array
     */
    protected function getParam($req, array $param = []): array
    {
        $type = $param['type'] ?? '';

        $param = [
            'app'  => [
                'name' => Config::get('app.name'),
            ],
            'api'  => [
                'meta' => [
                    'author' => [],
                    'email'  => Config::get('scaffold.api.meta.email'),
                ],
            ],
            'type' => $type,
            'auth' => [
                'user' => [
                    'id' => $req->user()->id,
                ],
            ],
            'link' => [
                'fullUrl' => $req->fullUrl(),
                'url'     => $req->url(),
            ],
        ];

        return Arr::dot($param);
    }
}