# Scaffold

## API

- [Postman API Doc](https://documenter.getpostman.com/view/1015471/S1LyVTUs)

### Get All Users

Example request

```http
GET /api/v1/users HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Page-Paging: 2
```

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Get Users

Example request

```http
GET /api/v1/users/9e556479-7003-5916-9cd6-33f4227cec9b HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
```

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Create Users

Example request

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

Example Response

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
            "anonymoussc"
        ]
    }
}
```

Notes:
- Success response will return http status `201 Created`

### Update Users

Example request

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

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Delete Users

Example request

```http
DELETE /api/v1/users/e2e74bb0-59e9-51ae-ab83-6e2621f4e8f3 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

Example Response

```json

```

Notes:
- Success response will return http status `204 No Content`

### Get All Roles

Example request

```http
GET /api/v1/roles HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Page-Paging: 2

```

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Get Roles

Example request

```curl
GET /api/v1/roles/4b166dbe-d99d-5091-abdd-95b83330ed3a HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Create Roles

Example request

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

Example Response

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
            "anonymoussc"
        ]
    }
}
```

Notes:
- Success response will return http status `201 Created`

### Update Roles

Example request

```curl
PATCH /api/v1/roles/ddcc8546-fe34-478c-b098-289c1240cfea HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

{
	"name":"janitor",
	"displayName":"Splash Master"
}
```

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Delete Roles

Example request

```curl
DELETE /api/v1/roles/ddcc8546-fe34-478c-b098-289c1240cfea HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

Example Response

```json

```

Notes:
- Success response will return http status `204 No Content`

### Get All Permissions

Example request

```http
GET /api/v1/permissions HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json
Page-Paging: 2

```

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Get Permissions

Example request

```http
GET /api/v1/permissions/e99caacd-6c45-5906-bd9f-b79e62f25963 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Create Permissions

Example request

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

Example Response

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
            "anonymoussc"
        ]
    }
}
```

Notes:
- Success response will return http status `201 Created`

### Update Permissions

Example request

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

Example Response

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
            "anonymoussc"
        ]
    }
}
```

### Delete Permissions

Example request

```curl
DELETE /api/v1/permissions/1f30f13f-363e-47fc-9695-e7eca585e330 HTTP/1.1
Host: localhost:8000
Accept: application/vnd.api+json
Content-Type: application/vnd.api+json

```

Example Response

```json

```

Notes:
- Success response will return http status `204 No Content`