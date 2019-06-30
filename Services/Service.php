<?php
/**
 * Service.php
 * Created by @anonymoussc on 01/03/2019 5:55 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/1/19 1:45 AM
 */

namespace App\Components\Scaffold\Services;

use App\Components\Signal\Shared\ErrorLog;
use App\Components\Signal\Shared\Signal;
use App\Components\Signature\Exceptions\NotAcceptableHttpException;
use App\Components\Signature\Exceptions\UnsupportedMediaTypeHttpException;
use App\Components\Signature\Services\SignatureService as BaseService;

/**
 * Class Service
 * @package App\Components\Passerby\Services
 */
class Service extends BaseService
{
    use Signal, ErrorLog;

    /**
     * @param array $option
     * @param array $param
     */
    public function verifyContentType($option = [], array $param = []): void
    {
        $headers = request()->headers->all();

        if (!isset($headers['content-type'])) {
            throw new UnsupportedMediaTypeHttpException('HTTP Request Content-Type header required');
        } else {
            if (!in_array('application/vnd.api+json', $headers['content-type'], true)) {
                throw new UnsupportedMediaTypeHttpException('Unsupported Media Type in HTTP request Content-Type header');
            }
        }
    }

    /**
     * @param array $option
     * @param array $param
     */
    public function verifyAcceptHeader($option = [], array $param = []): void
    {
        $headers = request()->headers->all();

        // dd($headers);
        if (!isset($headers['accept'])) {
            throw new NotAcceptableHttpException('HTTP Request Accept header required');
        } else {
            if (!in_array('application/vnd.api+json', $headers['accept'], true)) {
                throw new NotAcceptableHttpException('Not Acceptable value in HTTP request Accept header');
            }
        }
    }

    /**
     * @param bool  $mode
     * @param array $option
     * @param array $param
     */
    public function bootsJsonApi($mode = true, array $option = [], array $param = []): void
    {
        if ($mode) {
            $this->verifyAcceptHeader();
            $this->verifyContentType();
        }
    }
}
