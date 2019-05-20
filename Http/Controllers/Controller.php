<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/20/19 5:43 PM
 */

/**
 * Controller.php
 * Created by @anonymoussc on 04/08/2019 11:29 PM.
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Signal\Shared\ErrorLog;
use App\Components\Signal\Shared\Signal;
use App\Components\Signature\Http\Controllers\SignatureController as BaseController;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use JsonSerializable;
use Webpatser\Uuid\Uuid;

/**
 * Class Controller
 * @package App\Components\Passerby\Http\Controllers
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Signal, ErrorLog;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $euuid;

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
     * @param array $param
     *
     * @return array
     */
    protected function getOption(array $param = []): array
    {
        $request = App::get('request');
        $opt     = [];

        data_set($opt, 'api.hasLink', true);
        data_set($opt, 'api.hasMeta', true);

        return Arr::dot($opt);
    }

    /**
     * @param string $type
     * @param array  $param
     *
     * @return array
     */
    protected function getParam(string $type = '', array $param = []): array
    {
        $request = App::get('request');

        $par = [
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
                'user' => $request->user() ? $request->user()->toArray() : '',
            ],
            'link' => [
                'fullUrl' => $request->fullUrl(),
                'url'     => $request->url(),
            ],
        ];

        $newParam = Arr::dot($par);

        $newParam['api.meta.author'] = Config::get('scaffold.api.meta.author');
        $newParam['api.authors']     = Config::get('scaffold.api.authors');

        return $newParam;
    }

    /**
     * @return string
     */
    protected function getUuid(): string
    {
        return (string)Uuid::generate(4);
    }

    /**
     * @param       $id
     * @param       $errorObj
     * @param array $param
     *
     * @return array
     */
    protected function getErrorResponse($id, $errorObj, array $param = []): array
    {
        $request       = App::get('request');
        $errorResponse = [];

        data_set($errorResponse, 'error.id', $id);

        if ($errorObj instanceof \Exception) {
            data_set($errorResponse, 'error.code', $errorObj->getCode());
            data_set($errorResponse, 'error.title', $errorObj->getMessage());

            if (Config::get('app.env') !== 'production') {
                data_set($errorResponse, 'error.source.file', $errorObj->getFile());
                data_set($errorResponse, 'error.source.line', $errorObj->getLine());
                data_set($errorResponse, 'error.detail', $errorObj->getTraceAsString());
            }
        }

        data_set($errorResponse, 'link.self', $request->fullUrl());
        data_set($errorResponse, 'meta.copyright', 'copyrightⒸ ' . date('Y') . ' ' . Config::get('app.name'));
        data_set($errorResponse, 'meta.authors', Config::get('scaffold.api.authors'));

        return $errorResponse;
    }
}