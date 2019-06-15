<?php
/**
 * Controller.php
 * Created by @anonymoussc on 04/08/2019 11:29 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/13/19 2:16 AM
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
                'name' => Config::get('app.name') ?? Config::get('scaffold.name'),
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

        $newParam['api.authors']     = Config::get('scaffold.api.authors');

        return $newParam;
    }
}