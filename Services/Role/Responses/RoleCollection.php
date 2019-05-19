<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/19/19 2:51 PM
 */

/**
 * RoleCollection.php
 * Created by @anonymoussc on 05/10/2019 5:01 AM.
 */

namespace App\Components\Scaffold\Services\Role\Responses;

/**
 * Class RoleCollection
 * @package App\Components\Scaffold\Services\Role\Responses
 */
class RoleCollection
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
                        'name'        => $value->name,
                        'displayName' => $value->display_name,
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
                'current_page' => $data->currentPage(),
                'from'         => $data->firstItem(),
                'last_page'    => $data->lastPage(),
                'path'         => $param['link.url'],
                'per_page'     => $data->perPage(),
                'to'           => $data->lastItem(),
                'total'        => $data->total(),
            ];
        }

        $meta['copyright'] = 'copyrightⒸ ' . date('Y') . ' ' . $param['app.name'];
        $meta['author']    = $param['api.authors'];

        return $meta;
    }
}