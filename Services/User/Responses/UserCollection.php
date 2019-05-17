<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/17/19 8:19 AM
 */

/**
 * UserCollection.php
 * Created by @anonymoussc on 05/09/2019 6:44 PM.
 */

namespace App\Components\Scaffold\Services\User\Responses;

/**
 * Class UserCollection
 * @package App\Components\Scaffold\Services\User\Responses
 */
class UserCollection
{
    /**
     * @param       $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function __invoke($data, array $option = [], array $param = [])
    {
        $newData = [];
        $records = [];

        if ($data->isNotEmpty()) {
            $newData = $data->map(function($value, $key) use ($param) {
                return [
                    'type'       => $param['type'],
                    'id'         => $value->uuid,
                    'attributes' => [
                        'username' => $value->username,
                        'name'     => $value->name,
                        'email'    => $value->email,
                        'avatar'   => $value->avatar,
                        'settings' => $value->settings,
                    ],
                ];
            });

            $records['data'] = $newData;
            $records['link'] = $this->getLink($data, $param);
            $records['meta'] = $this->getMeta($data, $param);
        } else {
            $records['data'] = [];
            $records['link'] = $this->getLink($data, $param);
            $records['meta'] = $this->getMeta($data, $param);
        }

        return $records;
    }

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getLink($data, array $param = []): array
    {
        $link = [];

        if ($data->isNotEmpty()) {
            $link = [
                'first' => $data->url(1),
                'last'  => $data->url($data->lastPage()),
                'prev'  => $data->previousPageUrl(),
                'next'  => $data->nextPageUrl(),
            ];
        } else {
            $link['self'] = $param['link.fullUrl'];
        }

        return $link;
    }

    /**
     * @param       $data
     * @param array $param
     *
     * @return array
     */
    private function getMeta($data, array $param = []): array
    {
        $meta = [];

        if ($data->isNotEmpty()) {
            $meta = [
                "current_page" => $data->currentPage(),
                'from'         => $data->firstItem(),
                'last_page'    => $data->lastPage(),
                'path'         => $param['link.url'],
                'per_page'     => $data->perPage(),
                'to'           => $data->lastItem(),
                'total'        => $data->total(),
            ];
        }

        $meta['copyright'] = 'copyrightⒸ ' . date('Y') . ' ' . $param['app.name'];
        $meta['author']    = $param['api.meta.author'];

        return $meta;
    }
}