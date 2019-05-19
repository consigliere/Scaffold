# Scaffold

- [Postman API Doc](https://documenter.getpostman.com/view/1015471/S1LyVTUs)

## API

### Get All Users

#### Example request

```http
GET /api/v1/users HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Page-Paging: 2
```

#### Example Response

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
            }
        },
        {
            "type": "users",
            "id": "4be0643f-1d98-573b-97cd-ca98a65347dd",
            "attributes": {
                "username": "test",
                "name": "name test",
                "email": "name@test.com",
                "avatar": "",
                "settings": null
            }
        }
    ],
    "link": {
        "self": "http://localhost:8000/api/v1/users",
        "first": "http://localhost:8000/api/v1/users?page=1",
        "last": "http://localhost:8000/api/v1/users?page=17",
        "prev": null,
        "next": "http://localhost:8000/api/v1/users?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 17,
        "path": "http://localhost:8000/api/v1/users",
        "per_page": 2,
        "to": 2,
        "total": 33,
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

### Get Users

#### Example request

```http
GET /api/v1/users/9e556479-7003-5916-9cd6-33f4227cec9b HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
```

#### Example Response

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
        }
    },
    "link": {
        "self": "http://localhost:8000/api/v1/users/9e556479-7003-5916-9cd6-33f4227cec9b"
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

### Create Users

#### Example request

```http
POST /api/v1/users HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
    "roleId": "",
    "username": "JohnDoe",
    "name": "Moe Chung",
    "email": "johndoe@gmail.com",
    "avatar": "",
    "emailVerifiedAt": "",
    "password": "JohnDoe",
    "rememberToken": "",
    "settings": ""
}
```

#### Example Response

```json
{
    "data": {
        "type": "users",
        "id": "e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3",
        "attributes": {
            "username": "johndoe",
            "name": "Moe Chung",
            "email": "johndoe@gmail.com",
            "avatar": null,
            "settings": null
        }
    },
    "link": {
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
PATCH /api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
    "roleId": "",
    "username": "",
    "name": "Deryll Lomberto",
    "email": "",
    "avatar": "",
    "emailVerifiedAt": "",
    "password": "",
    "rememberToken": "",
    "settings": ""
}
```

#### Example Response

```json
{
    "data": {
        "type": "users",
        "id": "e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3",
        "attributes": {
            "username": "johndoe",
            "name": "Deryll Lomberto",
            "email": "johndoe@gmail.com",
            "avatar": "users/default.png",
            "settings": null
        }
    },
    "link": {
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

### Delete Users

#### Example request

```http
DELETE /api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

#### Example Response

```json

```

Notes:
- Success response will return http status `204 No Content`

### Get All Roles

#### Example request

```http
GET /api/v1/roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Page-Paging: 2

```

#### Example Response

```json
{
    "data": [
        {
            "type": "roles",
            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39",
            "attributes": {
                "name": "admin",
                "displayName": "Administrator"
            }
        },
        {
            "type": "roles",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "name": "user",
                "displayName": "Normal User"
            }
        }
    ],
    "link": {
        "self": "http://localhost:8000/api/v1/roles",
        "first": "http://localhost:8000/api/v1/roles?page=1",
        "last": "http://localhost:8000/api/v1/roles?page=3",
        "prev": null,
        "next": "http://localhost:8000/api/v1/roles?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 3,
        "path": "http://localhost:8000/api/v1/roles",
        "per_page": 2,
        "to": 2,
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
GET /api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

#### Example Response

```json
{
    "data": {
        "type": "roles",
        "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
        "attributes": {
            "name": "user",
            "displayName": "Normal User"
        }
    },
    "link": {
        "self": "http://localhost:8000/api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a"
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
	"name":"janitor",
	"displayName":"Cleaning"
}
```

#### Example Response

```json
{
    "data": {
        "type": "roles",
        "id": "ddcc8546-fe34-478c-b098-289c1240cfea",
        "attributes": {
            "name": "janitor",
            "displayName": "Cleaning"
        }
    },
    "link": {
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
PATCH /api/v1/roles/ddcc8546-fe34-478c-b098-289c1240cfea HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"name":"janitor",
	"displayName":"Splash Master"
}
```

#### Example Response

```json
{
    "data": {
        "type": "roles",
        "id": "ddcc8546-fe34-478c-b098-289c1240cfea",
        "attributes": {
            "name": "janitor",
            "displayName": "Splash Master"
        }
    },
    "link": {
        "self": "http://localhost:8000/api/v1/roles/ddcc8546-fe34-478c-b098-289c1240cfea"
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
DELETE /api/v1/roles/ddcc8546-fe34-478c-b098-289c1240cfea HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

#### Example Response

```json

```

Notes:
- Success response will return http status `204 No Content`

### Get All Permissions

#### Example request

```http
GET /api/v1/permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Page-Paging: 2

```

#### Example Response

```json
{
    "data": [
        {
            "type": "permissions",
            "id": "b04965e6-a9bb-591f-8f8a-1adcb2c8dc39",
            "attributes": {
                "key": "browse_admin",
                "entity": null
            }
        },
        {
            "type": "permissions",
            "id": "4b166dbe-d99d-5091-abdd-95b83330ed3a",
            "attributes": {
                "key": "browse_bread",
                "entity": null
            }
        }
    ],
    "link": {
        "self": "http://localhost:8000/api/v1/permissions",
        "first": "http://localhost:8000/api/v1/permissions?page=1",
        "last": "http://localhost:8000/api/v1/permissions?page=21",
        "prev": null,
        "next": "http://localhost:8000/api/v1/permissions?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 21,
        "path": "http://localhost:8000/api/v1/permissions",
        "per_page": 2,
        "to": 2,
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
Content-Type: application/vnd.api+json

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
    "link": {
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

#### Example Response

```json
{
    "data": {
        "type": "permissions",
        "id": "1f30f13f-363e-47fc-9695-e7eca585e330",
        "attributes": {
            "key": "can_do",
            "entity": "something"
        }
    },
    "link": {
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
PATCH /api/v1/permissions/1f30f13f-363e-47fc-9695-e7eca585e330 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"key":"edit_blog",
	"entity":"blogs"
}
```

#### Example Response

```json
{
    "data": {
        "type": "permissions",
        "id": "1f30f13f-363e-47fc-9695-e7eca585e330",
        "attributes": {
            "key": "edit_blog",
            "entity": "blogs"
        }
    },
    "link": {
        "self": "http://localhost:8000/api/v1/permissions/1f30f13f-363e-47fc-9695-e7eca585e330"
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
DELETE /api/v1/permissions/1f30f13f-363e-47fc-9695-e7eca585e330 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

#### Example Response

```json

```

Notes:
- Success response will return http status `204 No Content`

### Example error response in `production` environment

#### Example request

```http
GET /api/v1/users HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Page-Paging: 2

```

#### Example Response

```json
{
    "error": {
        "id": "5b107988-1028-4539-80cd-a498e92b49b1",
        "code": 0,
        "title": "Undefined variable: datax"
    },
    "link": {
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
Content-Type: application/vnd.api+json
Page-Paging: 2

```

#### Example Response

```json
{
    "error": {
        "id": "5268e481-a5f5-4b26-9259-a938c9d77caa",
        "code": 0,
        "title": "Undefined variable: datax",
        "source": {
            "file": "www\\public\\onsigbaar\\appdev\\app\\Components\\apppath\\Http\\Controllers\\UserController.php",
            "line": 87
        },
        "detail": "#0 www\\public\\onsigbaar\\appdev\\app\\Components\\apppath\\Http\\Controllers\\UserController.php(87): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(8, 'Undefined varia...', 'www\\\\public...', 87, Array)\n#1 [internal function]: App\\Components\\apppath\\Http\\Controllers\\UserController->browse(Object(Illuminate\\Http\\Request))\n#2 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Controller.php(54): call_user_func_array(Array, Array)\n#3 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\ControllerDispatcher.php(45): Illuminate\\Routing\\Controller->callAction('browse', Array)\n#4 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php(219): Illuminate\\Routing\\ControllerDispatcher->dispatch(Object(Illuminate\\Routing\\Route), Object(App\\Components\\apppath\\Http\\Controllers\\UserController), 'browse')\n#5 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Route.php(176): Illuminate\\Routing\\Route->runController()\n#6 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(680): Illuminate\\Routing\\Route->run()\n#7 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(30): Illuminate\\Routing\\Router->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#8 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\SubstituteBindings.php(41): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#9 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Routing\\Middleware\\SubstituteBindings->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#10 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#11 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Auth\\Middleware\\Authenticate.php(43): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#12 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Auth\\Middleware\\Authenticate->handle(Object(Illuminate\\Http\\Request), Object(Closure), 'api')\n#13 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#14 www\\public\\onsigbaar\\appdev\\app\\Components\\Signature\\Http\\Middleware\\AcceptJson.php(30): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#15 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): App\\Components\\Signature\\Http\\Middleware\\AcceptJson->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#16 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#17 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Middleware\\ThrottleRequests.php(58): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#18 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Routing\\Middleware\\ThrottleRequests->handle(Object(Illuminate\\Http\\Request), Object(Closure), 60, '1')\n#19 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#20 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#21 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(682): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(657): Illuminate\\Routing\\Router->runRouteWithinStack(Object(Illuminate\\Routing\\Route), Object(Illuminate\\Http\\Request))\n#23 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(623): Illuminate\\Routing\\Router->runRoute(Object(Illuminate\\Http\\Request), Object(Illuminate\\Routing\\Route))\n#24 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(612): Illuminate\\Routing\\Router->dispatchToRoute(Object(Illuminate\\Http\\Request))\n#25 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(176): Illuminate\\Routing\\Router->dispatch(Object(Illuminate\\Http\\Request))\n#26 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(30): Illuminate\\Foundation\\Http\\Kernel->Illuminate\\Foundation\\Http\\{closure}(Object(Illuminate\\Http\\Request))\n#27 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php(37): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#28 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#29 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#30 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php(66): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#31 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Cookie\\Middleware\\EncryptCookies->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#32 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#33 www\\public\\onsigbaar\\appdev\\vendor\\fideloper\\proxy\\src\\TrustProxies.php(57): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#34 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Fideloper\\Proxy\\TrustProxies->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#35 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#36 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php(21): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#37 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#38 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#39 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php(21): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#40 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#41 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#42 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize.php(27): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#43 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#44 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#45 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode.php(62): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#46 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(163): Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode->handle(Object(Illuminate\\Http\\Request), Object(Closure))\n#47 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))\n#48 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(104): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#49 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(151): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#50 www\\public\\onsigbaar\\appdev\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))\n#51 www\\public\\onsigbaar\\appdev\\public\\index.php(55): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))\n#52 www\\public\\onsigbaar\\appdev\\server.php(21): require_once('www\\\\public...')\n#53 {main}"
    },
    "link": {
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
