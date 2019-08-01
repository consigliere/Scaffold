# Scaffold

- [Postman API Doc](https://documenter.getpostman.com/view/1015471/S1LyVTUs)

- [Middleware](#middleware)
    - [Check For All Scopes](#check-for-all-scopes)
    - [Check For Any Scopes](#check-for-any-scopes)
- [API Testing](#api-testing)
    - [Publish Tests File](#publish-tests-file)
    - [Run PhpUnit](#run-phpunit)
- [API](#api)
    - [Get Users Profile](#get-users-profile)
    - [Get All Users](#get-all-users)
        - [Example request](#example-request)
        - [Example response 200](#example-response-200)
        - [Example response 406](#example-response-406)
    - [Get Users](#get-users)
        - [Example request](#example-request)
        - [Example response 200](#example-response-200)
        - [Example response 404](#example-response-404)
    - [Create Users](#create-users)
    - [Update Users](#update-users)
    - [Delete Users](#delete-users)
    - [Delete Multiple Users](#delete-multiple-users)
    - [Get Users Relationship Primary Role](#get-users-relationship-primary-role)
    - [Get Users Primary Role](#get-users-primary-role)
    - [Get Users Relationships Additional Roles](#get-users-relationships-additional-roles)
    - [Get Users Additional Roles](#get-users-additional-roles)
    - [Synchronize Users Additional Roles](#synchronize-users-additional-roles)
    - [Add Users Additional Roles](#add-users-additional-roles)
    - [Remove Users Additional Roles](#remove-users-additional-roles)
    - [Get All Roles](#get-all-roles)
    - [Get Roles](#get-roles)
    - [Create Roles](#create-roles)
    - [Update Roles](#update-roles)
    - [Delete Roles](#delete-roles)
    - [Get Roles Relationship Permissions](#get-roles-relationship-permissions)
    - [Get Roles Permissions](#get-roles-permissions)
    - [Synchronize Roles Permissions](#synchronize-roles-permissions)
    - [Add Roles Permissions](#add-roles-permissions)
    - [Remove Roles Permissions](#remove-roles-permissions)
    - [Get All Permissions](#get-all-permissions)
    - [Get Permissions](#get-permissions)
    - [Create Permissions](#create-permissions)
    - [Update Permissions](#update-permissions)
    - [Delete Permissions](#delete-permissions)
    - [Example error response in `production` environment](#example-error-response-in-production-environment)
    - [Example error response in __not__ `production` environment](#example-error-response-in-not-production-environment)

## Middleware

```php
# App\Http\Kernel.php

    ...
    protected $routeMiddleware = [
        ...
        'scopes' => \App\Components\Signature\Http\Middleware\CheckScopes::class,
        'scope'  => \App\Components\Signature\Http\Middleware\CheckForAnyScope::class,
        ...
    ];
    ...
```

### Check For All Scopes

The `scopes` middleware may be assigned to a route to verify that the incoming request's access token has _all_ of the listed scopes:

```php
Route::get('/orders', function () {
    // Access token has both "check-status" and "place-orders" scopes...
})->middleware('scopes:check-status,place-orders');
```

### Check For Any Scopes

The `scope` middleware may be assigned to a route to verify that the incoming request's access token has _at least one_ of the listed scopes:

```php
Route::get('/orders', function () {
    // Access token has either "check-status" or "place-orders" scope...
})->middleware('scope:check-status,place-orders');
```

## API Testing

All API test file extend from `ScaffoldApiTestCase`.

```php
namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

/**
 * Class ScaffoldApiTestCase
 * @package Tests
 */
abstract class ScaffoldApiTestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    public function init()
    {
        $scopes = \App\Components\Scaffold\Entities\Permission::where('id', '>', 0)->pluck('key');

        Passport::actingAs(
            factory(\Api\User\Entities\User::class)->create(['role_id' => 2, 'uuid' => randomUuid(), 'username' => 'test' . mt_rand()]),
            $scopes->toArray()
        );
    }
}
```

### Publish Tests File

```bash
php artisan vendor:publish --tag=scaffold_tests --force
```

### Run PhpUnit

```bash
phpunit
```

## API

### Get Users Profile

#### Example request

```http
GET /api/v1/users/profile HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 200

```json
{
    "data": {
        "type": "users",
        "id": "9e556479-7003-5916-9cd6-33f4227cec9b",
        "attributes": {
            "username": "user",
            "name": "user",
            "email": "user@api.com",
            "avatar": "users/default.png",
            "settings": null
        },
        "relationships": {
            "primary-role": {
                "links": {
                    "self": "http://localhost:8000/api/v1/users/9e556479-7003-5916-9cd6-33f4227cec9b/relationships/primary-role",
                    "related": "http://localhost:8000/api/v1/users/9e556479-7003-5916-9cd6-33f4227cec9b/primary-role"
                },
                "data": {
                    "type": "roles",
                    "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
                }
            },
            "additional-roles": []
        }
    },
    "included": [
        {
            "type": "roles",
            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39",
            "attributes": {
                "name": "admin",
                "displayName": "Administrator"
            },
            "relationships": {
                "permissions": {
                    "data": [
                        {
                            "type": "permissions",
                            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
                        },
                        {
                            "type": "permissions",
                            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
                        },
                        {
                            "type": "permissions",
                            "id": "98123fde-012f-5ff3-8b50-881449dac91a"
                        },
                        {
                            "type": "permissions",
                            "id": "6ed955c6-506a-5343-9be4-2c0afae02eef"
                        },
                        {
                            "type": "permissions",
                            "id": "c8691da2-158a-5ed6-8537-0e6f140801f2"
                        },
                        {
                            "type": "permissions",
                            "id": "a6c4fc8f-6950-51de-a9ae-2c519c465071"
                        },
                        {
                            "type": "permissions",
                            "id": "a9f96b98-dd44-5216-ab0d-dbfc6b262edf"
                        },
                        {
                            "type": "permissions",
                            "id": "e99caacd-6c45-5906-bd9f-b79e62f25963"
                        },
                        {
                            "type": "permissions",
                            "id": "e4d80b30-151e-51b5-9f4f-18a3b82718e6"
                        },
                        {
                            "type": "permissions",
                            "id": "0159d6c7-973f-5e7a-a9a0-d195d0ea6fe2"
                        },
                        {
                            "type": "permissions",
                            "id": "7fef88f7-411d-5669-b42d-bf5fc7f9b58b"
                        },
                        {
                            "type": "permissions",
                            "id": "52524d6e-10dc-5261-aa36-8b2efcbaa5f0"
                        },
                        {
                            "type": "permissions",
                            "id": "91c274f2-9a0d-5ce6-ac3d-7529f452df21"
                        },
                        {
                            "type": "permissions",
                            "id": "0ff1e264-520d-543a-87dd-181a491e667e"
                        },
                        {
                            "type": "permissions",
                            "id": "23986425-d3a5-5e13-8bab-299745777a8d"
                        },
                        {
                            "type": "permissions",
                            "id": "c15b38c9-9a3e-543c-a703-dd742f25b4d5"
                        },
                        {
                            "type": "permissions",
                            "id": "db680066-c83d-5ed7-89a4-1d79466ea62d"
                        },
                        {
                            "type": "permissions",
                            "id": "cadb7952-2bba-5609-88d4-8e47ec4e7920"
                        },
                        {
                            "type": "permissions",
                            "id": "35140057-a2a4-5adb-a500-46f8ed8b66a9"
                        },
                        {
                            "type": "permissions",
                            "id": "66e549b7-01e2-5d07-98d5-430f74d8d3b2"
                        },
                        {
                            "type": "permissions",
                            "id": "292c8e99-2378-55aa-83d8-350e0ac3f1cc"
                        },
                        {
                            "type": "permissions",
                            "id": "0e3b230a-0509-55d8-96a0-9875f387a2be"
                        },
                        {
                            "type": "permissions",
                            "id": "4c507660-a83b-55c0-9b2b-83eccb07723d"
                        },
                        {
                            "type": "permissions",
                            "id": "a1b9b633-da11-58be-b1a9-5cfa2848f186"
                        },
                        {
                            "type": "permissions",
                            "id": "c2708a8b-120a-56f5-a30d-990048af87cc"
                        },
                        {
                            "type": "permissions",
                            "id": "e7263999-68b6-5a23-b530-af25b7efd632"
                        },
                        {
                            "type": "permissions",
                            "id": "ce1ae2d5-3454-5952-97ff-36ff935bcfe9"
                        },
                        {
                            "type": "permissions",
                            "id": "33677b87-bc8d-5ff6-9a25-fe60225e4bf0"
                        },
                        {
                            "type": "permissions",
                            "id": "ed2305ae-e8f9-5387-b860-3d80ae6c02f7"
                        },
                        {
                            "type": "permissions",
                            "id": "604ed872-ae2d-5d91-8e3e-572f3a3aaaa5"
                        },
                        {
                            "type": "permissions",
                            "id": "8f8173d9-2f8d-5636-a693-24d9f79ba651"
                        },
                        {
                            "type": "permissions",
                            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                        },
                        {
                            "type": "permissions",
                            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                        },
                        {
                            "type": "permissions",
                            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
                        },
                        {
                            "type": "permissions",
                            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337"
                        },
                        {
                            "type": "permissions",
                            "id": "8828c9d6-ed76-5c09-bf64-ba9e9cd90896"
                        },
                        {
                            "type": "permissions",
                            "id": "facb7618-55ca-5c30-9cba-fd567b6c0611"
                        },
                        {
                            "type": "permissions",
                            "id": "96f3de0e-6412-5434-b406-67ef3352ab85"
                        },
                        {
                            "type": "permissions",
                            "id": "9ebacb89-40ab-52b3-93a2-9054611d8f55"
                        },
                        {
                            "type": "permissions",
                            "id": "681046ff-9129-5ade-b11c-769864e02184"
                        },
                        {
                            "type": "permissions",
                            "id": "c13d0b5d-1ca3-57b6-a23f-8586bca44928"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users/profile"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get All Users

#### Example request

```http
GET /api/v1/users HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
X-Page-Paging: 5
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "users",
            "id": "9e556479-7003-5916-9cd6-33f4227cec9b",
            "attributes": {
                "username": "user",
                "name": "user",
                "email": "user@api.com",
                "avatar": "users/default.png",
                "settings": null
            },
            "relationships": {
                "primary-role": {
                    "links": {
                        "self": "http://localhost:8000/api/v1/users/9e556479-7003-5916-9cd6-33f4227cec9b/relationships/primary-role",
                        "related": "http://localhost:8000/api/v1/users/9e556479-7003-5916-9cd6-33f4227cec9b/primary-role"
                    },
                    "data": {
                        "type": "roles",
                        "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
                    }
                },
                "additional-roles": []
            },
            "links": {
                "self": "http://localhost:8000/api/v1/users/9e556479-7003-5916-9cd6-33f4227cec9b"
            }
        },
        {
            "type": "users",
            "id": "e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3",
            "attributes": {
                "username": "johndoe",
                "name": "John Doe",
                "email": "johndoe@email.com",
                "avatar": "users/default.png",
                "settings": null
            },
            "relationships": {
                "primary-role": {
                    "links": {
                        "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/relationships/primary-role",
                        "related": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/primary-role"
                    },
                    "data": {
                        "type": "roles",
                        "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
                    }
                },
                "additional-roles": {
                    "links": {
                        "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/relationships/additional-roles",
                        "related": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/additional-roles"
                    },
                    "data": [
                        {
                            "type": "roles",
                            "id": "b480ec6a-2557-4fdb-b543-017243f8e001"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3"
            }
        },
        {
            "type": "users",
            "id": "9cbec665-52a6-5876-9fbb-9aaa0c6772e0",
            "attributes": {
                "username": "janedoe",
                "name": "Jane Doe",
                "email": "janedoe@email.com",
                "avatar": "users/default.png",
                "settings": null
            },
            "relationships": {
                "primary-role": {
                    "links": {
                        "self": "http://localhost:8000/api/v1/users/9cbec665-52a6-5876-9fbb-9aaa0c6772e0/relationships/primary-role",
                        "related": "http://localhost:8000/api/v1/users/9cbec665-52a6-5876-9fbb-9aaa0c6772e0/primary-role"
                    },
                    "data": {
                        "type": "roles",
                        "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
                    }
                },
                "additional-roles": {
                    "links": {
                        "self": "http://localhost:8000/api/v1/users/9cbec665-52a6-5876-9fbb-9aaa0c6772e0/relationships/additional-roles",
                        "related": "http://localhost:8000/api/v1/users/9cbec665-52a6-5876-9fbb-9aaa0c6772e0/additional-roles"
                    },
                    "data": [
                        {
                            "type": "roles",
                            "id": "832683f9-fee8-4ce4-b275-398fb3a8eda4"
                        },
                        {
                            "type": "roles",
                            "id": "88aeb931-dc0c-45aa-96eb-532751ea4ae9"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/users/9cbec665-52a6-5876-9fbb-9aaa0c6772e0"
            }
        }
    ],
    "included": [
        {
            "type": "roles",
            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39",
            "attributes": {
                "name": "admin",
                "displayName": "Administrator"
            },
            "relationships": {
                "permissions": {
                    "data": [
                        {
                            "type": "permissions",
                            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
                        },
                        {
                            "type": "permissions",
                            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
                        },
                        {
                            "type": "permissions",
                            "id": "98123fde-012f-5ff3-8b50-881449dac91a"
                        },
                        {
                            "type": "permissions",
                            "id": "6ed955c6-506a-5343-9be4-2c0afae02eef"
                        },
                        {
                            "type": "permissions",
                            "id": "c8691da2-158a-5ed6-8537-0e6f140801f2"
                        },
                        {
                            "type": "permissions",
                            "id": "a6c4fc8f-6950-51de-a9ae-2c519c465071"
                        },
                        {
                            "type": "permissions",
                            "id": "a9f96b98-dd44-5216-ab0d-dbfc6b262edf"
                        },
                        {
                            "type": "permissions",
                            "id": "e99caacd-6c45-5906-bd9f-b79e62f25963"
                        },
                        {
                            "type": "permissions",
                            "id": "e4d80b30-151e-51b5-9f4f-18a3b82718e6"
                        },
                        {
                            "type": "permissions",
                            "id": "0159d6c7-973f-5e7a-a9a0-d195d0ea6fe2"
                        },
                        {
                            "type": "permissions",
                            "id": "7fef88f7-411d-5669-b42d-bf5fc7f9b58b"
                        },
                        {
                            "type": "permissions",
                            "id": "52524d6e-10dc-5261-aa36-8b2efcbaa5f0"
                        },
                        {
                            "type": "permissions",
                            "id": "91c274f2-9a0d-5ce6-ac3d-7529f452df21"
                        },
                        {
                            "type": "permissions",
                            "id": "0ff1e264-520d-543a-87dd-181a491e667e"
                        },
                        {
                            "type": "permissions",
                            "id": "23986425-d3a5-5e13-8bab-299745777a8d"
                        },
                        {
                            "type": "permissions",
                            "id": "c15b38c9-9a3e-543c-a703-dd742f25b4d5"
                        },
                        {
                            "type": "permissions",
                            "id": "db680066-c83d-5ed7-89a4-1d79466ea62d"
                        },
                        {
                            "type": "permissions",
                            "id": "cadb7952-2bba-5609-88d4-8e47ec4e7920"
                        },
                        {
                            "type": "permissions",
                            "id": "35140057-a2a4-5adb-a500-46f8ed8b66a9"
                        },
                        {
                            "type": "permissions",
                            "id": "66e549b7-01e2-5d07-98d5-430f74d8d3b2"
                        },
                        {
                            "type": "permissions",
                            "id": "292c8e99-2378-55aa-83d8-350e0ac3f1cc"
                        },
                        {
                            "type": "permissions",
                            "id": "0e3b230a-0509-55d8-96a0-9875f387a2be"
                        },
                        {
                            "type": "permissions",
                            "id": "4c507660-a83b-55c0-9b2b-83eccb07723d"
                        },
                        {
                            "type": "permissions",
                            "id": "a1b9b633-da11-58be-b1a9-5cfa2848f186"
                        },
                        {
                            "type": "permissions",
                            "id": "c2708a8b-120a-56f5-a30d-990048af87cc"
                        },
                        {
                            "type": "permissions",
                            "id": "e7263999-68b6-5a23-b530-af25b7efd632"
                        },
                        {
                            "type": "permissions",
                            "id": "ce1ae2d5-3454-5952-97ff-36ff935bcfe9"
                        },
                        {
                            "type": "permissions",
                            "id": "33677b87-bc8d-5ff6-9a25-fe60225e4bf0"
                        },
                        {
                            "type": "permissions",
                            "id": "ed2305ae-e8f9-5387-b860-3d80ae6c02f7"
                        },
                        {
                            "type": "permissions",
                            "id": "604ed872-ae2d-5d91-8e3e-572f3a3aaaa5"
                        },
                        {
                            "type": "permissions",
                            "id": "8f8173d9-2f8d-5636-a693-24d9f79ba651"
                        },
                        {
                            "type": "permissions",
                            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                        },
                        {
                            "type": "permissions",
                            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                        },
                        {
                            "type": "permissions",
                            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
                        },
                        {
                            "type": "permissions",
                            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337"
                        },
                        {
                            "type": "permissions",
                            "id": "8828c9d6-ed76-5c09-bf64-ba9e9cd90896"
                        },
                        {
                            "type": "permissions",
                            "id": "facb7618-55ca-5c30-9cba-fd567b6c0611"
                        },
                        {
                            "type": "permissions",
                            "id": "96f3de0e-6412-5434-b406-67ef3352ab85"
                        },
                        {
                            "type": "permissions",
                            "id": "9ebacb89-40ab-52b3-93a2-9054611d8f55"
                        },
                        {
                            "type": "permissions",
                            "id": "681046ff-9129-5ade-b11c-769864e02184"
                        },
                        {
                            "type": "permissions",
                            "id": "c13d0b5d-1ca3-57b6-a23f-8586bca44928"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
            }
        },
        {
            "type": "roles",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "name": "user",
                "displayName": "Normal User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a"
            }
        },
        {
            "type": "roles",
            "id": "b480ec6a-2557-4fdb-b543-017243f8e001",
            "attributes": {
                "name": "author",
                "displayName": "Author User"
            },
            "relationships": {
                "permissions": {
                    "data": [
                        {
                            "type": "permissions",
                            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                        },
                        {
                            "type": "permissions",
                            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                        },
                        {
                            "type": "permissions",
                            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
                        },
                        {
                            "type": "permissions",
                            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001"
            }
        },
        {
            "type": "roles",
            "id": "832683f9-fee8-4ce4-b275-398fb3a8eda4",
            "attributes": {
                "name": "editor",
                "displayName": "Editor User"
            },
            "relationships": {
                "permissions": {
                    "data": [
                        {
                            "type": "permissions",
                            "id": "8f8173d9-2f8d-5636-a693-24d9f79ba651"
                        },
                        {
                            "type": "permissions",
                            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                        },
                        {
                            "type": "permissions",
                            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/832683f9-fee8-4ce4-b275-398fb3a8eda4"
            }
        },
        {
            "type": "roles",
            "id": "88aeb931-dc0c-45aa-96eb-532751ea4ae9",
            "attributes": {
                "name": "tax_specialist",
                "displayName": "Tax Specialist"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9"
            }
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/v1/users?page=1",
        "last": "http://localhost:8000/api/v1/users?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://localhost:8000/api/v1/users",
        "per_page": 5,
        "to": 3,
        "total": 3,
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

#### Example response 406

```json
{
    "error": {
        "id": "a142f488-53ad-452d-83ef-61231534caac",
        "status": "406",
        "code": "0",
        "title": "Not Acceptable value in HTTP request Accept header"
    },
    "links": {
        "self": "http://localhost:8000/api/v1/users"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "authors": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get Users

#### Example request

```http
GET /api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 200

```json
{
    "data": {
        "type": "users",
        "id": "e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3",
        "attributes": {
            "username": "johndoe",
            "name": "John Doe",
            "email": "johndoe@email.com",
            "avatar": "users/default.png",
            "settings": null
        },
        "relationships": {
            "primary-role": {
                "links": {
                    "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/relationships/primary-role",
                    "related": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/primary-role"
                },
                "data": {
                    "type": "roles",
                    "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
                }
            },
            "additional-roles": {
                "links": {
                    "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/relationships/additional-roles",
                    "related": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/additional-roles"
                },
                "data": [
                    {
                        "type": "roles",
                        "id": "b480ec6a-2557-4fdb-b543-017243f8e001"
                    }
                ]
            }
        }
    },
    "included": [
        {
            "type": "roles",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "name": "user",
                "displayName": "Normal User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a"
            }
        },
        {
            "type": "roles",
            "id": "b480ec6a-2557-4fdb-b543-017243f8e001",
            "attributes": {
                "name": "author",
                "displayName": "Author User"
            },
            "relationships": {
                "permissions": {
                    "data": [
                        {
                            "type": "permissions",
                            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                        },
                        {
                            "type": "permissions",
                            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                        },
                        {
                            "type": "permissions",
                            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
                        },
                        {
                            "type": "permissions",
                            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

#### Example response 404

```json
{
    "error": {
        "id": "b3d9c5b5-1f1c-4720-ba46-a2fcbabe9690",
        "status": "404",
        "code": "0",
        "title": "Cannot find Users resources in URI query parameter /6436c87b-6769-4ecb-a2a2-79d786e913f1x"
    },
    "links": {
        "self": "http://localhost:8000/api/v1/users/6436c87b-6769-4ecb-a2a2-79d786e913f1x"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "authors": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Create Users

#### Example request

```http
POST /api/v1/users HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
{
    "roleId": "",
    "username": "dummytests",
    "name": "Dummy Tests",
    "email": "dummytests@email.com",
    "avatar": "",
    "emailVerifiedAt": "",
    "password": "dummytests",
    "rememberToken": "",
    "settings": ""
}
```

#### Example Response 201

```json
{
    "data": {
        "type": "users",
        "id": "575c6cab-09de-58d2-bbf0-ea7a0ba0b438",
        "attributes": {
            "username": "dummytests",
            "name": "Dummy Tests",
            "email": "dummytests@email.com",
            "avatar": "users/default.png",
            "settings": null
        },
        "relationships": {
            "primary-role": {
                "links": {
                    "self": "http://localhost:8000/api/v1/users/575c6cab-09de-58d2-bbf0-ea7a0ba0b438/relationships/primary-role",
                    "related": "http://localhost:8000/api/v1/users/575c6cab-09de-58d2-bbf0-ea7a0ba0b438/primary-role"
                },
                "data": {
                    "type": "roles",
                    "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
                }
            },
            "additional-roles": []
        }
    },
    "included": [
        {
            "type": "roles",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "name": "user",
                "displayName": "Normal User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

Notes:
- Success response will return http status `201 Created`

### Update Users

#### Example request

```http
PATCH /api/v1/users/575c6cab-09de-58d2-bbf0-ea7a0ba0b438 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
    "roleId": "",
    "username": "dummytests2",
    "name": "Dummy Tests2",
    "email": "dummytests2@email.com",
    "avatar": "",
    "emailVerifiedAt": "",
    "password": "",
    "rememberToken": "",
    "settings": ""
}
```

#### Example response 200

```json
{
    "data": {
        "type": "users",
        "id": "575c6cab-09de-58d2-bbf0-ea7a0ba0b438",
        "attributes": {
            "username": "dummytests2",
            "name": "Dummy Tests2",
            "email": "dummytests2@email.com",
            "avatar": "users/default.png",
            "settings": null
        },
        "relationships": {
            "primary-role": {
                "links": {
                    "self": "http://localhost:8000/api/v1/users/575c6cab-09de-58d2-bbf0-ea7a0ba0b438/relationships/primary-role",
                    "related": "http://localhost:8000/api/v1/users/575c6cab-09de-58d2-bbf0-ea7a0ba0b438/primary-role"
                },
                "data": {
                    "type": "roles",
                    "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
                }
            },
            "additional-roles": []
        }
    },
    "included": [
        {
            "type": "roles",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "name": "user",
                "displayName": "Normal User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users/575c6cab-09de-58d2-bbf0-ea7a0ba0b438"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Delete Users

#### Example request

```http
DELETE /api/v1/users/0e1c52c7-c0b1-5e24-9154-0f29c7546cd8 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 204

```json

```

Notes:
- Success response will return http status `204 No Content`
- Multiple delete can be done, by input multiple uuid into URI separated by comma

### Delete Multiple Users

#### Example request

```http
DELETE /api/v1/users/575c6cab-09de-58d2-bbf0-ea7a0ba0b438,e1c5a0bd-497d-5f23-98e1-24f7f16c3831 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 204

```json

```

Notes:
- Success response will return http status `204 No Content`

### Get Users Relationship Primary Role

#### Example request

```http
GET /api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/relationships/primary-role HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 200

```json
{
    "data": {
        "type": "roles",
        "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
    },
    "links": {
        "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/relationships/primary-role"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get Users Primary Role

#### Example request

```http
GET /api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/primary-role HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 200

```json
{
    "data": {
        "type": "roles",
        "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
        "attributes": {
            "name": "user",
            "displayName": "Normal User"
        },
        "links": {
            "self": "http://localhost:8000/api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a"
        }
    },
    "links": {
        "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/primary-role"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get Users Relationships Additional Roles

#### Example request

```http
GET /api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/relationships/additional-roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "roles",
            "id": "b480ec6a-2557-4fdb-b543-017243f8e001"
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/relationships/additional-roles",
        "related": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/additional-roles"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get Users Additional Roles

#### Example request

```http
GET /api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/additional-roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "roles",
            "id": "b480ec6a-2557-4fdb-b543-017243f8e001",
            "attributes": {
                "name": "author",
                "displayName": "Author User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3/additional-roles"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Synchronize Users Additional Roles

#### Example request

```http
PATCH /api/v1/users/2a304264-814c-587d-a537-6a9501eb4b9c/relationships/sync-additional-roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"roles":["88aeb931-dc0c-45aa-96eb-532751ea4ae9","b480ec6a-2557-4fdb-b543-017243f8e001"]
}
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "roles",
            "id": "b480ec6a-2557-4fdb-b543-017243f8e001",
            "attributes": {
                "name": "author",
                "displayName": "Author User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001"
            }
        },
        {
            "type": "roles",
            "id": "88aeb931-dc0c-45aa-96eb-532751ea4ae9",
            "attributes": {
                "name": "tax_specialist",
                "displayName": "Tax Specialist"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users/2a304264-814c-587d-a537-6a9501eb4b9c/relationships/sync-additional-roles"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Add Users Additional Roles

#### Example request

```http
PATCH /api/v1/users/2a304264-814c-587d-a537-6a9501eb4b9c/relationships/add-additional-roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"roles": ["832683f9-fee8-4ce4-b275-398fb3a8eda4"]
}
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "roles",
            "id": "b480ec6a-2557-4fdb-b543-017243f8e001",
            "attributes": {
                "name": "author",
                "displayName": "Author User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001"
            }
        },
        {
            "type": "roles",
            "id": "832683f9-fee8-4ce4-b275-398fb3a8eda4",
            "attributes": {
                "name": "editor",
                "displayName": "Editor User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/832683f9-fee8-4ce4-b275-398fb3a8eda4"
            }
        },
        {
            "type": "roles",
            "id": "88aeb931-dc0c-45aa-96eb-532751ea4ae9",
            "attributes": {
                "name": "tax_specialist",
                "displayName": "Tax Specialist"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users/2a304264-814c-587d-a537-6a9501eb4b9c/relationships/add-additional-roles"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Remove Users Additional Roles

#### Example request

```http
PATCH /api/v1/users/2a304264-814c-587d-a537-6a9501eb4b9c/relationships/remove-additional-roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"roles": ["88aeb931-dc0c-45aa-96eb-532751ea4ae9"]
}
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "roles",
            "id": "b480ec6a-2557-4fdb-b543-017243f8e001",
            "attributes": {
                "name": "author",
                "displayName": "Author User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001"
            }
        },
        {
            "type": "roles",
            "id": "832683f9-fee8-4ce4-b275-398fb3a8eda4",
            "attributes": {
                "name": "editor",
                "displayName": "Editor User"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/832683f9-fee8-4ce4-b275-398fb3a8eda4"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/users/2a304264-814c-587d-a537-6a9501eb4b9c/relationships/remove-additional-roles"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get All Roles

#### Example request

```http
GET /api/v1/roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
X-Page-Paging: 5
```

#### Example Response 200

```json
{
    "data": [
        {
            "type": "roles",
            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39",
            "attributes": {
                "name": "admin",
                "displayName": "Administrator"
            },
            "relationship": {
                "permissions": {
                    "links": {
                        "self": "http://localhost:8000/api/v1/roles/b04965e6-a9bb-591f-8f8a-1adcb2c8dc39/relationships/permissions",
                        "related": "http://localhost:8000/api/v1/roles/b04965e6-a9bb-591f-8f8a-1adcb2c8dc39/permissions"
                    },
                    "data": [
                        {
                            "type": "permissions",
                            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
                        },
                        {
                            "type": "permissions",
                            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a"
                        },
                        {
                            "type": "permissions",
                            "id": "98123fde-012f-5ff3-8b50-881449dac91a"
                        },
                        {
                            "type": "permissions",
                            "id": "6ed955c6-506a-5343-9be4-2c0afae02eef"
                        },
                        {
                            "type": "permissions",
                            "id": "c8691da2-158a-5ed6-8537-0e6f140801f2"
                        },
                        {
                            "type": "permissions",
                            "id": "a6c4fc8f-6950-51de-a9ae-2c519c465071"
                        },
                        {
                            "type": "permissions",
                            "id": "a9f96b98-dd44-5216-ab0d-dbfc6b262edf"
                        },
                        {
                            "type": "permissions",
                            "id": "e99caacd-6c45-5906-bd9f-b79e62f25963"
                        },
                        {
                            "type": "permissions",
                            "id": "e4d80b30-151e-51b5-9f4f-18a3b82718e6"
                        },
                        {
                            "type": "permissions",
                            "id": "0159d6c7-973f-5e7a-a9a0-d195d0ea6fe2"
                        },
                        {
                            "type": "permissions",
                            "id": "7fef88f7-411d-5669-b42d-bf5fc7f9b58b"
                        },
                        {
                            "type": "permissions",
                            "id": "52524d6e-10dc-5261-aa36-8b2efcbaa5f0"
                        },
                        {
                            "type": "permissions",
                            "id": "91c274f2-9a0d-5ce6-ac3d-7529f452df21"
                        },
                        {
                            "type": "permissions",
                            "id": "0ff1e264-520d-543a-87dd-181a491e667e"
                        },
                        {
                            "type": "permissions",
                            "id": "23986425-d3a5-5e13-8bab-299745777a8d"
                        },
                        {
                            "type": "permissions",
                            "id": "c15b38c9-9a3e-543c-a703-dd742f25b4d5"
                        },
                        {
                            "type": "permissions",
                            "id": "db680066-c83d-5ed7-89a4-1d79466ea62d"
                        },
                        {
                            "type": "permissions",
                            "id": "cadb7952-2bba-5609-88d4-8e47ec4e7920"
                        },
                        {
                            "type": "permissions",
                            "id": "35140057-a2a4-5adb-a500-46f8ed8b66a9"
                        },
                        {
                            "type": "permissions",
                            "id": "66e549b7-01e2-5d07-98d5-430f74d8d3b2"
                        },
                        {
                            "type": "permissions",
                            "id": "292c8e99-2378-55aa-83d8-350e0ac3f1cc"
                        },
                        {
                            "type": "permissions",
                            "id": "0e3b230a-0509-55d8-96a0-9875f387a2be"
                        },
                        {
                            "type": "permissions",
                            "id": "4c507660-a83b-55c0-9b2b-83eccb07723d"
                        },
                        {
                            "type": "permissions",
                            "id": "a1b9b633-da11-58be-b1a9-5cfa2848f186"
                        },
                        {
                            "type": "permissions",
                            "id": "c2708a8b-120a-56f5-a30d-990048af87cc"
                        },
                        {
                            "type": "permissions",
                            "id": "e7263999-68b6-5a23-b530-af25b7efd632"
                        },
                        {
                            "type": "permissions",
                            "id": "ce1ae2d5-3454-5952-97ff-36ff935bcfe9"
                        },
                        {
                            "type": "permissions",
                            "id": "33677b87-bc8d-5ff6-9a25-fe60225e4bf0"
                        },
                        {
                            "type": "permissions",
                            "id": "ed2305ae-e8f9-5387-b860-3d80ae6c02f7"
                        },
                        {
                            "type": "permissions",
                            "id": "604ed872-ae2d-5d91-8e3e-572f3a3aaaa5"
                        },
                        {
                            "type": "permissions",
                            "id": "8f8173d9-2f8d-5636-a693-24d9f79ba651"
                        },
                        {
                            "type": "permissions",
                            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                        },
                        {
                            "type": "permissions",
                            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                        },
                        {
                            "type": "permissions",
                            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
                        },
                        {
                            "type": "permissions",
                            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337"
                        },
                        {
                            "type": "permissions",
                            "id": "8828c9d6-ed76-5c09-bf64-ba9e9cd90896"
                        },
                        {
                            "type": "permissions",
                            "id": "facb7618-55ca-5c30-9cba-fd567b6c0611"
                        },
                        {
                            "type": "permissions",
                            "id": "96f3de0e-6412-5434-b406-67ef3352ab85"
                        },
                        {
                            "type": "permissions",
                            "id": "9ebacb89-40ab-52b3-93a2-9054611d8f55"
                        },
                        {
                            "type": "permissions",
                            "id": "681046ff-9129-5ade-b11c-769864e02184"
                        },
                        {
                            "type": "permissions",
                            "id": "c13d0b5d-1ca3-57b6-a23f-8586bca44928"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b04965e6-a9bb-591f-8f8a-1adcb2c8dc39",
                "related": "http://localhost:8000/api/v1/roles/b04965e6-a9bb-591f-8f8a-1adcb2c8dc39/permissions"
            }
        },
        {
            "type": "roles",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "name": "user",
                "displayName": "Normal User"
            },
            "relationship": {
                "permissions": []
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a",
                "related": "http://localhost:8000/api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a/permissions"
            }
        },
        {
            "type": "roles",
            "id": "b480ec6a-2557-4fdb-b543-017243f8e001",
            "attributes": {
                "name": "author",
                "displayName": "Author User"
            },
            "relationship": {
                "permissions": {
                    "links": {
                        "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/relationships/permissions",
                        "related": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/permissions"
                    },
                    "data": [
                        {
                            "type": "permissions",
                            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                        },
                        {
                            "type": "permissions",
                            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                        },
                        {
                            "type": "permissions",
                            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
                        },
                        {
                            "type": "permissions",
                            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001",
                "related": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/permissions"
            }
        },
        {
            "type": "roles",
            "id": "832683f9-fee8-4ce4-b275-398fb3a8eda4",
            "attributes": {
                "name": "editor",
                "displayName": "Editor User"
            },
            "relationship": {
                "permissions": {
                    "links": {
                        "self": "http://localhost:8000/api/v1/roles/832683f9-fee8-4ce4-b275-398fb3a8eda4/relationships/permissions",
                        "related": "http://localhost:8000/api/v1/roles/832683f9-fee8-4ce4-b275-398fb3a8eda4/permissions"
                    },
                    "data": [
                        {
                            "type": "permissions",
                            "id": "8f8173d9-2f8d-5636-a693-24d9f79ba651"
                        },
                        {
                            "type": "permissions",
                            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                        },
                        {
                            "type": "permissions",
                            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                        }
                    ]
                }
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/832683f9-fee8-4ce4-b275-398fb3a8eda4",
                "related": "http://localhost:8000/api/v1/roles/832683f9-fee8-4ce4-b275-398fb3a8eda4/permissions"
            }
        },
        {
            "type": "roles",
            "id": "88aeb931-dc0c-45aa-96eb-532751ea4ae9",
            "attributes": {
                "name": "tax_specialist",
                "displayName": "Tax Specialist"
            },
            "relationship": {
                "permissions": []
            },
            "links": {
                "self": "http://localhost:8000/api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9",
                "related": "http://localhost:8000/api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9/permissions"
            }
        }
    ],
    "included": [
        {
            "type": "permissions",
            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39",
            "attributes": {
                "key": "browse_admin",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
            }
        },
        {
            "type": "permissions",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "key": "browse_bread",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/4b166dbe-d99d-5091-abdd-95b83330ed3a"
            }
        },
        {
            "type": "permissions",
            "id": "98123fde-012f-5ff3-8b50-881449dac91a",
            "attributes": {
                "key": "browse_database",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/98123fde-012f-5ff3-8b50-881449dac91a"
            }
        },
        {
            "type": "permissions",
            "id": "6ed955c6-506a-5343-9be4-2c0afae02eef",
            "attributes": {
                "key": "browse_media",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/6ed955c6-506a-5343-9be4-2c0afae02eef"
            }
        },
        {
            "type": "permissions",
            "id": "c8691da2-158a-5ed6-8537-0e6f140801f2",
            "attributes": {
                "key": "browse_compass",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/c8691da2-158a-5ed6-8537-0e6f140801f2"
            }
        },
        {
            "type": "permissions",
            "id": "a6c4fc8f-6950-51de-a9ae-2c519c465071",
            "attributes": {
                "key": "browse_menus",
                "entity": "menus"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/a6c4fc8f-6950-51de-a9ae-2c519c465071"
            }
        },
        {
            "type": "permissions",
            "id": "a9f96b98-dd44-5216-ab0d-dbfc6b262edf",
            "attributes": {
                "key": "read_menus",
                "entity": "menus"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/a9f96b98-dd44-5216-ab0d-dbfc6b262edf"
            }
        },
        {
            "type": "permissions",
            "id": "e99caacd-6c45-5906-bd9f-b79e62f25963",
            "attributes": {
                "key": "edit_menus",
                "entity": "menus"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/e99caacd-6c45-5906-bd9f-b79e62f25963"
            }
        },
        {
            "type": "permissions",
            "id": "e4d80b30-151e-51b5-9f4f-18a3b82718e6",
            "attributes": {
                "key": "add_menus",
                "entity": "menus"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/e4d80b30-151e-51b5-9f4f-18a3b82718e6"
            }
        },
        {
            "type": "permissions",
            "id": "0159d6c7-973f-5e7a-a9a0-d195d0ea6fe2",
            "attributes": {
                "key": "delete_menus",
                "entity": "menus"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/0159d6c7-973f-5e7a-a9a0-d195d0ea6fe2"
            }
        },
        {
            "type": "permissions",
            "id": "7fef88f7-411d-5669-b42d-bf5fc7f9b58b",
            "attributes": {
                "key": "browse_roles",
                "entity": "roles"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/7fef88f7-411d-5669-b42d-bf5fc7f9b58b"
            }
        },
        {
            "type": "permissions",
            "id": "52524d6e-10dc-5261-aa36-8b2efcbaa5f0",
            "attributes": {
                "key": "read_roles",
                "entity": "roles"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/52524d6e-10dc-5261-aa36-8b2efcbaa5f0"
            }
        },
        {
            "type": "permissions",
            "id": "91c274f2-9a0d-5ce6-ac3d-7529f452df21",
            "attributes": {
                "key": "edit_roles",
                "entity": "roles"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/91c274f2-9a0d-5ce6-ac3d-7529f452df21"
            }
        },
        {
            "type": "permissions",
            "id": "0ff1e264-520d-543a-87dd-181a491e667e",
            "attributes": {
                "key": "add_roles",
                "entity": "roles"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/0ff1e264-520d-543a-87dd-181a491e667e"
            }
        },
        {
            "type": "permissions",
            "id": "23986425-d3a5-5e13-8bab-299745777a8d",
            "attributes": {
                "key": "delete_roles",
                "entity": "roles"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/23986425-d3a5-5e13-8bab-299745777a8d"
            }
        },
        {
            "type": "permissions",
            "id": "c15b38c9-9a3e-543c-a703-dd742f25b4d5",
            "attributes": {
                "key": "browse_users",
                "entity": "users"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/c15b38c9-9a3e-543c-a703-dd742f25b4d5"
            }
        },
        {
            "type": "permissions",
            "id": "db680066-c83d-5ed7-89a4-1d79466ea62d",
            "attributes": {
                "key": "read_users",
                "entity": "users"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/db680066-c83d-5ed7-89a4-1d79466ea62d"
            }
        },
        {
            "type": "permissions",
            "id": "cadb7952-2bba-5609-88d4-8e47ec4e7920",
            "attributes": {
                "key": "edit_users",
                "entity": "users"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/cadb7952-2bba-5609-88d4-8e47ec4e7920"
            }
        },
        {
            "type": "permissions",
            "id": "35140057-a2a4-5adb-a500-46f8ed8b66a9",
            "attributes": {
                "key": "add_users",
                "entity": "users"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/35140057-a2a4-5adb-a500-46f8ed8b66a9"
            }
        },
        {
            "type": "permissions",
            "id": "66e549b7-01e2-5d07-98d5-430f74d8d3b2",
            "attributes": {
                "key": "delete_users",
                "entity": "users"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/66e549b7-01e2-5d07-98d5-430f74d8d3b2"
            }
        },
        {
            "type": "permissions",
            "id": "292c8e99-2378-55aa-83d8-350e0ac3f1cc",
            "attributes": {
                "key": "browse_settings",
                "entity": "settings"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/292c8e99-2378-55aa-83d8-350e0ac3f1cc"
            }
        },
        {
            "type": "permissions",
            "id": "0e3b230a-0509-55d8-96a0-9875f387a2be",
            "attributes": {
                "key": "read_settings",
                "entity": "settings"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/0e3b230a-0509-55d8-96a0-9875f387a2be"
            }
        },
        {
            "type": "permissions",
            "id": "4c507660-a83b-55c0-9b2b-83eccb07723d",
            "attributes": {
                "key": "edit_settings",
                "entity": "settings"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/4c507660-a83b-55c0-9b2b-83eccb07723d"
            }
        },
        {
            "type": "permissions",
            "id": "a1b9b633-da11-58be-b1a9-5cfa2848f186",
            "attributes": {
                "key": "add_settings",
                "entity": "settings"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/a1b9b633-da11-58be-b1a9-5cfa2848f186"
            }
        },
        {
            "type": "permissions",
            "id": "c2708a8b-120a-56f5-a30d-990048af87cc",
            "attributes": {
                "key": "delete_settings",
                "entity": "settings"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/c2708a8b-120a-56f5-a30d-990048af87cc"
            }
        },
        {
            "type": "permissions",
            "id": "e7263999-68b6-5a23-b530-af25b7efd632",
            "attributes": {
                "key": "browse_categories",
                "entity": "categories"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/e7263999-68b6-5a23-b530-af25b7efd632"
            }
        },
        {
            "type": "permissions",
            "id": "ce1ae2d5-3454-5952-97ff-36ff935bcfe9",
            "attributes": {
                "key": "read_categories",
                "entity": "categories"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/ce1ae2d5-3454-5952-97ff-36ff935bcfe9"
            }
        },
        {
            "type": "permissions",
            "id": "33677b87-bc8d-5ff6-9a25-fe60225e4bf0",
            "attributes": {
                "key": "edit_categories",
                "entity": "categories"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/33677b87-bc8d-5ff6-9a25-fe60225e4bf0"
            }
        },
        {
            "type": "permissions",
            "id": "ed2305ae-e8f9-5387-b860-3d80ae6c02f7",
            "attributes": {
                "key": "add_categories",
                "entity": "categories"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/ed2305ae-e8f9-5387-b860-3d80ae6c02f7"
            }
        },
        {
            "type": "permissions",
            "id": "604ed872-ae2d-5d91-8e3e-572f3a3aaaa5",
            "attributes": {
                "key": "delete_categories",
                "entity": "categories"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/604ed872-ae2d-5d91-8e3e-572f3a3aaaa5"
            }
        },
        {
            "type": "permissions",
            "id": "8f8173d9-2f8d-5636-a693-24d9f79ba651",
            "attributes": {
                "key": "browse_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/8f8173d9-2f8d-5636-a693-24d9f79ba651"
            }
        },
        {
            "type": "permissions",
            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5",
            "attributes": {
                "key": "read_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/36eb8d4d-b854-51f1-9fdf-3735964225d5"
            }
        },
        {
            "type": "permissions",
            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0",
            "attributes": {
                "key": "edit_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
            }
        },
        {
            "type": "permissions",
            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5",
            "attributes": {
                "key": "add_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
            }
        },
        {
            "type": "permissions",
            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337",
            "attributes": {
                "key": "delete_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/f468d924-d23b-56c2-b90f-3d1cf4b45337"
            }
        },
        {
            "type": "permissions",
            "id": "8828c9d6-ed76-5c09-bf64-ba9e9cd90896",
            "attributes": {
                "key": "browse_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/8828c9d6-ed76-5c09-bf64-ba9e9cd90896"
            }
        },
        {
            "type": "permissions",
            "id": "facb7618-55ca-5c30-9cba-fd567b6c0611",
            "attributes": {
                "key": "read_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/facb7618-55ca-5c30-9cba-fd567b6c0611"
            }
        },
        {
            "type": "permissions",
            "id": "96f3de0e-6412-5434-b406-67ef3352ab85",
            "attributes": {
                "key": "edit_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/96f3de0e-6412-5434-b406-67ef3352ab85"
            }
        },
        {
            "type": "permissions",
            "id": "9ebacb89-40ab-52b3-93a2-9054611d8f55",
            "attributes": {
                "key": "add_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/9ebacb89-40ab-52b3-93a2-9054611d8f55"
            }
        },
        {
            "type": "permissions",
            "id": "681046ff-9129-5ade-b11c-769864e02184",
            "attributes": {
                "key": "delete_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/681046ff-9129-5ade-b11c-769864e02184"
            }
        },
        {
            "type": "permissions",
            "id": "c13d0b5d-1ca3-57b6-a23f-8586bca44928",
            "attributes": {
                "key": "browse_hooks",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/c13d0b5d-1ca3-57b6-a23f-8586bca44928"
            }
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/v1/roles?page=1",
        "last": "http://localhost:8000/api/v1/roles?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://localhost:8000/api/v1/roles",
        "per_page": 5,
        "to": 5,
        "total": 5,
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get Roles

#### Example request

```http
GET /api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example Response 200

```json
{
    "data": {
        "type": "roles",
        "id": "b480ec6a-2557-4fdb-b543-017243f8e001",
        "attributes": {
            "name": "author",
            "displayName": "Author User"
        },
        "relationships": {
            "permissions": {
                "links": {
                    "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/relationships/permissions",
                    "related": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/permissions"
                },
                "data": [
                    {
                        "type": "permissions",
                        "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
                    },
                    {
                        "type": "permissions",
                        "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
                    },
                    {
                        "type": "permissions",
                        "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
                    },
                    {
                        "type": "permissions",
                        "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337"
                    }
                ]
            }
        }
    },
    "included": [
        {
            "type": "permissions",
            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5",
            "attributes": {
                "key": "read_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/36eb8d4d-b854-51f1-9fdf-3735964225d5"
            }
        },
        {
            "type": "permissions",
            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0",
            "attributes": {
                "key": "edit_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
            }
        },
        {
            "type": "permissions",
            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5",
            "attributes": {
                "key": "add_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
            }
        },
        {
            "type": "permissions",
            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337",
            "attributes": {
                "key": "delete_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/f468d924-d23b-56c2-b90f-3d1cf4b45337"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Create Roles

#### Example request

```http
POST /api/v1/roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"name":"dummy",
	"displayName":"dummy roles"
}
```

#### Example Response 201

```json
{
    "data": {
        "type": "roles",
        "id": "3806c81f-f4f3-42ef-88ea-b76f962d836d",
        "attributes": {
            "name": "dummy",
            "displayName": "dummy roles"
        },
        "relationships": {
            "permissions": []
        }
    },
    "included": [],
    "links": {
        "self": "http://localhost:8000/api/v1/roles"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

Notes:
- Success response will return http status `201 Created`

### Update Roles

#### Example request

```http
PATCH /api/v1/roles/3806c81f-f4f3-42ef-88ea-b76f962d836d HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"name":"demo",
	"displayName":"Demo Roles"
}
```

#### Example Response

```json
{
    "data": {
        "type": "roles",
        "id": "3806c81f-f4f3-42ef-88ea-b76f962d836d",
        "attributes": {
            "name": "demo",
            "displayName": "Demo Roles"
        },
        "relationships": {
            "permissions": []
        }
    },
    "included": [],
    "links": {
        "self": "http://localhost:8000/api/v1/roles/3806c81f-f4f3-42ef-88ea-b76f962d836d"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Delete Roles

#### Example request

```http
DELETE /api/v1/roles/3806c81f-f4f3-42ef-88ea-b76f962d836d HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example Response 204

```json

```

Notes:
- Success response will return http status `204 No Content`
- Multiple delete can be done, by input multiple uuid into URI separated by comma

### Get Roles Relationship Permissions

#### Example request

```http
GET /api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/relationships/permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "permissions",
            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5"
        },
        {
            "type": "permissions",
            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
        },
        {
            "type": "permissions",
            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
        },
        {
            "type": "permissions",
            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337"
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/relationships/permissions"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get Roles Permissions

#### Example request

```http
GET /api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "permissions",
            "id": "36eb8d4d-b854-51f1-9fdf-3735964225d5",
            "attributes": {
                "key": "read_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/36eb8d4d-b854-51f1-9fdf-3735964225d5"
            }
        },
        {
            "type": "permissions",
            "id": "3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0",
            "attributes": {
                "key": "edit_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/3493b6ca-f84b-56a9-97cc-c0bd1c46c4c0"
            }
        },
        {
            "type": "permissions",
            "id": "f413ea13-fcd9-5b44-9d22-1fa1f7b063a5",
            "attributes": {
                "key": "add_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/f413ea13-fcd9-5b44-9d22-1fa1f7b063a5"
            }
        },
        {
            "type": "permissions",
            "id": "f468d924-d23b-56c2-b90f-3d1cf4b45337",
            "attributes": {
                "key": "delete_posts",
                "entity": "posts"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/f468d924-d23b-56c2-b90f-3d1cf4b45337"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/roles/b480ec6a-2557-4fdb-b543-017243f8e001/permissions"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Synchronize Roles Permissions

#### Example request

```http
PATCH /api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9/relationships/sync-permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"permissions":["8828c9d6-ed76-5c09-bf64-ba9e9cd90896","facb7618-55ca-5c30-9cba-fd567b6c0611"]
}
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "permissions",
            "id": "8828c9d6-ed76-5c09-bf64-ba9e9cd90896",
            "attributes": {
                "key": "browse_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/8828c9d6-ed76-5c09-bf64-ba9e9cd90896"
            }
        },
        {
            "type": "permissions",
            "id": "facb7618-55ca-5c30-9cba-fd567b6c0611",
            "attributes": {
                "key": "read_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/facb7618-55ca-5c30-9cba-fd567b6c0611"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9/relationships/sync-permissions"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Add Roles Permissions

#### Example request

```http
PATCH /api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9/relationships/add-permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"permissions":["96f3de0e-6412-5434-b406-67ef3352ab85"]
}
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "permissions",
            "id": "8828c9d6-ed76-5c09-bf64-ba9e9cd90896",
            "attributes": {
                "key": "browse_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/8828c9d6-ed76-5c09-bf64-ba9e9cd90896"
            }
        },
        {
            "type": "permissions",
            "id": "facb7618-55ca-5c30-9cba-fd567b6c0611",
            "attributes": {
                "key": "read_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/facb7618-55ca-5c30-9cba-fd567b6c0611"
            }
        },
        {
            "type": "permissions",
            "id": "96f3de0e-6412-5434-b406-67ef3352ab85",
            "attributes": {
                "key": "edit_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/96f3de0e-6412-5434-b406-67ef3352ab85"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9/relationships/add-permissions"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Remove Roles Permissions

#### Example request

```http
PATCH /api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9/relationships/remove-permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"permissions":["96f3de0e-6412-5434-b406-67ef3352ab85"]
}
```

#### Example response 200

```json
{
    "data": [
        {
            "type": "permissions",
            "id": "8828c9d6-ed76-5c09-bf64-ba9e9cd90896",
            "attributes": {
                "key": "browse_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/8828c9d6-ed76-5c09-bf64-ba9e9cd90896"
            }
        },
        {
            "type": "permissions",
            "id": "facb7618-55ca-5c30-9cba-fd567b6c0611",
            "attributes": {
                "key": "read_pages",
                "entity": "pages"
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/facb7618-55ca-5c30-9cba-fd567b6c0611"
            }
        }
    ],
    "links": {
        "self": "http://localhost:8000/api/v1/roles/88aeb931-dc0c-45aa-96eb-532751ea4ae9/relationships/remove-permissions"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get All Permissions

#### Example request

```http
GET /api/v1/permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
X-Page-Paging: 5
```

#### Example Response 200

```json
{
    "data": [
        {
            "type": "permissions",
            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39",
            "attributes": {
                "key": "browse_admin",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/b04965e6-a9bb-591f-8f8a-1adcb2c8dc39"
            }
        },
        {
            "type": "permissions",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "key": "browse_bread",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/4b166dbe-d99d-5091-abdd-95b83330ed3a"
            }
        },
        {
            "type": "permissions",
            "id": "98123fde-012f-5ff3-8b50-881449dac91a",
            "attributes": {
                "key": "browse_database",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/98123fde-012f-5ff3-8b50-881449dac91a"
            }
        },
        {
            "type": "permissions",
            "id": "6ed955c6-506a-5343-9be4-2c0afae02eef",
            "attributes": {
                "key": "browse_media",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/6ed955c6-506a-5343-9be4-2c0afae02eef"
            }
        },
        {
            "type": "permissions",
            "id": "c8691da2-158a-5ed6-8537-0e6f140801f2",
            "attributes": {
                "key": "browse_compass",
                "entity": null
            },
            "links": {
                "self": "http://localhost:8000/api/v1/permissions/c8691da2-158a-5ed6-8537-0e6f140801f2"
            }
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/v1/permissions?page=1",
        "last": "http://localhost:8000/api/v1/permissions?page=9",
        "prev": null,
        "next": "http://localhost:8000/api/v1/permissions?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 9,
        "path": "http://localhost:8000/api/v1/permissions",
        "per_page": 5,
        "to": 5,
        "total": 41,
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Get Permissions

#### Example request

```http
GET /api/v1/permissions/e99caacd-6c45-5906-bd9f-b79e62f25963 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example Response

```json
{
    "data": {
        "type": "permissions",
        "id": "e99caacd-6c45-5906-bd9f-b79e62f25963",
        "attributes": {
            "key": "edit_menus",
            "entity": "menus"
        }
    },
    "links": {
        "self": "http://localhost:8000/api/v1/permissions/e99caacd-6c45-5906-bd9f-b79e62f25963"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Create Permissions

#### Example request

```http
POST /api/v1/permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"key":"can_do",
	"entity":"something"
}
```

#### Example Response 201

```json
{
    "data": {
        "type": "permissions",
        "id": "14fd19a8-904c-4b2c-bd96-2f479658e72d",
        "attributes": {
            "key": "can_do",
            "entity": "something"
        },
        "links": {
            "self": "http://localhost:8000/api/v1/permissions/14fd19a8-904c-4b2c-bd96-2f479658e72d"
        }
    },
    "links": {
        "self": "http://localhost:8000/api/v1/permissions"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

Notes:
- Success response will return http status `201 Created`

### Update Permissions

#### Example request

```http
PATCH /api/v1/permissions/14fd19a8-904c-4b2c-bd96-2f479658e72d HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"key":"can_do_updated",
	"entity":"something_updated"
}
```

#### Example Response

```json
{
    "data": {
        "type": "permissions",
        "id": "14fd19a8-904c-4b2c-bd96-2f479658e72d",
        "attributes": {
            "key": "can_do_updated",
            "entity": "something_updated"
        },
        "links": {
            "self": "http://localhost:8000/api/v1/permissions/14fd19a8-904c-4b2c-bd96-2f479658e72d"
        }
    },
    "links": {
        "self": "http://localhost:8000/api/v1/permissions/14fd19a8-904c-4b2c-bd96-2f479658e72d"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "author": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Delete Permissions

#### Example request

```http
DELETE /api/v1/permissions/14fd19a8-904c-4b2c-bd96-2f479658e72d HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
```

#### Example Response

```json

```

Notes:
- Success response will return http status `204 No Content`
- Multiple delete can be done, by input multiple uuid into URI separated by comma

### Example error response in `production` environment

#### Example request

```http
GET /api/v1/users HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
X-Page-Paging: 5
```

#### Example Response

```json
{
    "error": {
        "id": "2f867357-ff8f-4729-ac26-53ce3f765c5d",
        "status": "500",
        "code": "0",
        "title": "Undefined variable: datax"
    },
    "links": {
        "self": "http://localhost:8000/api/v1/users"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "authors": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```

### Example error response in __not__ `production` environment

#### Example request

```http
GET /api/v1/users HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
X-Page-Paging: 5
```

#### Example Response

```json
{
    "error": {
        "id": "ab109c66-45cb-486b-9230-410c4717f8df",
        "status": "500",
        "code": "0",
        "title": "Undefined variable: datax",
        "source": {
            "file": "www\\base\\app\\Components\\Scaffold\\Http\\Controllers\\UserController.php",
            "line": 77
        },
        "detail": "#0 www\\base\\app\\Components\\Scaffold\\Http\\Controllers\\UserController.php(77): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(8, 'Undefined varia...', 'www\\\\...', 77, Array)\n#1 [internal function]: App\\Components\\Scaffold\\Http\\Controllers\\UserController->browse(Object(Illuminate\\Http\\Request))\n#2 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Controller.php(54): call_user_func_array(Array, Array)\n#3 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\ControllerDispatcher.php(45): Illuminate\\Routing\\Controller->callAction('browse', Array)\n#4 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php(219): Illuminate\\Routing\\ControllerDispatcher->dispatch(Object(Illuminate\\Routing\\Route), Object(App\\Components\\Scaffold\\Http\\Controllers\\UserController), 'browse')\n#5 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php(176): Illuminate\\Routing\\Route->runController()\n#6 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(680): Illuminate\\Routing\\Route->run()\n#7 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(30): Illuminate\\Routing\\Router->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#8 www\\base\\app\\Components\\Signature\\Http\\Middleware\\CheckScopes.php(45): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#9 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): App\\Components\\Signature\\Http\\Middleware\\CheckScopes->handle(Object(Illuminate\\Http\\Request), Object(Closure), 'browse_users')\n#10 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#11 www\\base\\app\\Components\\Signature\\Http\\Middleware\\CheckAcceptHttpHeader.php(50): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#12 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): App\\Components\\Signature\\Http\\Middleware\\CheckAcceptHttpHeader->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#13 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#14 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\SubstituteBindings.php(41): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#15 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Routing\\Middleware\\SubstituteBindings->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#16 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#17 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\Middleware\\Authenticate.php(43): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#18 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Auth\\Middleware\\Authenticate->handle(Object(Illuminate\\Http\\Request), Object(Closure), 'api')\n#19 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#20 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php(58): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#21 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Routing\\Middleware\\ThrottleRequests->handle(Object(Illuminate\\Http\\Request), Object(Closure), 60, '1')\n#22 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#23 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#24 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(682): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(657): Illuminate\\Routing\\Router->runRouteWithinStack(Object(Illuminate\\Routing\\Route), Object(Illuminate\\Http\\Request))\n#26 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(623): Illuminate\\Routing\\Router->runRoute(Object(Illuminate\\Http\\Request), Object(Illuminate\\Routing\\Route))\n#27 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(612): Illuminate\\Routing\\Router->dispatchToRoute(Object(Illuminate\\Http\\Request))\n#28 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(176): Illuminate\\Routing\\Router->dispatch(Object(Illuminate\\Http\\Request))\n#29 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(30): Illuminate\\Foundation\\Http\\Kernel->Illuminate\\Foundation\\Http\\{closure}(Object(Illuminate\\Http\\Request))\n#30 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php(37): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#31 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#32 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#33 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php(66): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#34 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Cookie\\Middleware\\EncryptCookies->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#35 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#36 www\\base\\vendor\\fideloper\\proxy\\src\\TrustProxies.php(57): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#37 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Fideloper\\Proxy\\TrustProxies->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#38 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#39 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php(21): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#40 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#41 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#42 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php(21): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#43 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#44 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#45 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize.php(27): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#46 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#47 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#48 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode.php(62): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#49 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#50 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#51 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#52 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(151): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#53 www\\base\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))\n#54 www\\base\\public\\index.php(55): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))\n#55 www\\base\\server.php(21): require_once('www\\\\...')\n#56 {main}"
    },
    "links": {
        "self": "http://localhost:8000/api/v1/users"
    },
    "meta": {
        "copyright": "copyrightⒸ 2019 Onsigbaar",
        "authors": [
            {
                "name": "anonymoussc",
                "email": "50c5ac69@opayq.com"
            }
        ]
    }
}
```
