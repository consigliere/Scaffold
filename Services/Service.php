<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/15/19 6:57 AM
 */

/**
 * Service.php
 * Created by @anonymoussc on 01/03/2019 5:55 PM.
 */

namespace App\Components\Scaffold\Services;

use App\Components\Signal\Shared\ErrorLog;
use App\Components\Signal\Shared\Signal;
use App\Components\Signature\Services\SignatureService as BaseService;

/**
 * Class Service
 * @package App\Components\Passerby\Services
 */
class Service extends BaseService
{
    use Signal, ErrorLog;

    /**
     * @param callable $reform
     * @param array    $data
     * @param array    $option
     * @param array    $param
     *
     * @return array
     */
    public function reform(Callable $reform, array $data = [], array $option = [], $param = []): array
    {
        return $reform($data, $option, $param);
    }

    /**
     * @param callable $response
     * @param          $dataObj
     * @param array    $option
     * @param array    $param
     *
     * @return array
     */
    public function transform(Callable $response, $dataObj, array $option = [], array $param = []): array
    {
        return $response($dataObj, $option, $param);
    }
}
