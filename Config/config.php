<?php
/**
 * config.php
 * Created by @anonymoussc on 03/11/2019 7:30 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/26/19 11:17 PM
 */

return [
    'name'    => 'Scaffold',
    'version' => 'v0.2.0',
    'api'     => [
        'authors'     => [
            [
                'name'  => 'anonymoussc',
                'email' => '50c5ac69@opayq.com',
            ],
        ],
        'page_paging' => 5,
        'users'       => [
            'type'            => 'users',
            'hasLink'         => true,
            'hasMeta'         => true,
            'hasRelationship' => true,
            'hasIncluded'     => true,
            'authors'         => [
                [
                    'name'  => 'anonymoussc',
                    'email' => '50c5ac69@opayq.com',
                ],
            ],
        ],
        'permissions' => [
            'type'            => 'permissions',
            'hasLink'         => true,
            'hasMeta'         => true,
            'hasRelationship' => true,
            'hasIncluded'     => true,
            'authors'         => [
                [
                    'name'  => 'anonymoussc',
                    'email' => '50c5ac69@opayq.com',
                ],
            ],
        ],
        'roles'       => [
            'type'            => 'roles',
            'hasLink'         => true,
            'hasMeta'         => true,
            'hasRelationship' => true,
            'hasIncluded'     => true,
            'authors'         => [
                [
                    'name'  => 'anonymoussc',
                    'email' => '50c5ac69@opayq.com',
                ],
            ],
        ],
    ],
];
