<?php
/**
 * PermissionService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/16/19 11:41 PM
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\PermissionRepositoryInterface;
use App\Components\Scaffold\Services\Permission\Requests\CreatePermission;
use App\Components\Scaffold\Services\Permission\Requests\UpdatePermission;
use App\Components\Scaffold\Services\Permission\Responses\PermissionCollection;
use App\Components\Scaffold\Services\Permission\Responses\PermissionResource;
use App\Components\Signature\Exceptions\BadRequestHttpException;
use App\Components\Signature\Exceptions\NotFoundHttpException;
use Illuminate\Foundation\Application;

/**
 * Class PermissionService
 * @package App\Components\Scaffold\Services
 */
class PermissionService extends Service
{
    /**
     * @var \App\Components\Scaffold\Repositories\PermissionRepositoryInterface
     */
    private $permissionRepository;

    /**
     * @var \Illuminate\Auth\AuthManager|mixed
     */
    private $auth;

    /**
     * @var mixed
     */
    private $request;

    /**
     * PermissionService constructor.
     *
     * @param \Illuminate\Foundation\Application                                  $app
     * @param \App\Components\Scaffold\Repositories\PermissionRepositoryInterface $PermissionRepository
     */
    public function __construct(Application $app, PermissionRepositoryInterface $PermissionRepository)
    {
        $this->permissionRepository = $PermissionRepository;

        $this->auth    = $app->make('auth');
        $this->request = $app->make('request');
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function browse(array $data = [], array $option = [], array $param = []): array
    {
        $roles = $this->permissionRepository->browse($data);

        return (new PermissionCollection)($roles);
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function create(array $data, array $option = [], array $param = []): array
    {
        $permissionKey = $this->permissionRepository->getWhere('key', data_get($data, 'form.key'));

        if ($permissionKey->isNotEmpty()) {
            throw new BadRequestHttpException('Permission name key already exists, please try another');
        }

        $newPermission = (new CreatePermission)($data);
        $permission    = $this->permissionRepository->create($newPermission);

        return (new PermissionResource)($permission);
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function read($uuid, array $data, array $option = [], array $param = []): array
    {
        $id = $this->permissionRepository->getIdbyUuid($uuid);

        if (null === $id) {
            throw new BadRequestHttpException('Can\'t find Permission with ID #' . $uuid);
        }

        $permission = $this->permissionRepository->getById($id);

        if (null === $permission) {
            throw new NotFoundHttpException('Permission not found');
        }

        return (new PermissionResource)($permission);
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function update($uuid, array $data, array $option = [], array $param = []): array
    {
        $id = $this->permissionRepository->getIdbyUuid($uuid);

        if (null === $id) {
            throw new BadRequestHttpException('Can\'t find Permission with ID #' . $uuid);
        }

        if (null !== data_get($data, 'form.key')) {
            $roleName = $this->permissionRepository->getWhere('key', data_get($data, 'form.key'));

            if ($roleName->isNotEmpty()) {
                throw new BadRequestHttpException('Permission name key already exists, please try another');
            }
        }

        $newPermission = (new UpdatePermission)($data);
        $permission    = $this->permissionRepository->update($id, $newPermission);

        return (new PermissionResource)($permission);
    }

    /**
     * @param       $uuid
     * @param array $param
     */
    public function delete($uuid, array $param = []): void
    {
        $ids = explode(',', $uuid);

        foreach ($ids as $id) {
            $id = $this->permissionRepository->getIdbyUuid($id);

            if (null === $id) {
                throw new BadRequestHttpException('Can\'t find Permission with ID #' . $uuid);
            }

            $this->permissionRepository->delete($id);
        }
    }
}