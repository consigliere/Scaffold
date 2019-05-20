<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/19/19 11:51 PM
 */

/**
 * PermissionService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\PermissionRepositoryInterface;
use App\Components\Scaffold\Services\Permission\Requests\CreatePermission;
use App\Components\Scaffold\Services\Permission\Requests\UpdatePermission;
use App\Components\Scaffold\Services\Permission\Responses\PermissionCollection;
use App\Components\Scaffold\Services\Permission\Responses\PermissionResource;
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
     * PermissionService constructor.
     *
     * @param \Illuminate\Foundation\Application                                  $app
     * @param \App\Components\Scaffold\Repositories\PermissionRepositoryInterface $PermissionRepository
     */
    public function __construct(Application $app, PermissionRepositoryInterface $PermissionRepository)
    {
        $this->permissionRepository = $PermissionRepository;
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

        return $this->transform(new PermissionCollection, $roles, $option, $param);
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
        $newRole = $this->reform(new CreatePermission, $data);
        $role    = $this->permissionRepository->create($newRole, $option, $param);

        return $this->transform(new PermissionResource, $role, $option, $param);
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
        $id   = $this->permissionRepository->getIdFromUuid($uuid) ?? $uuid;
        $role = $this->permissionRepository->getById($id);

        return $this->transform(new PermissionResource, $role, $option, $param);
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
        $id      = $this->permissionRepository->getIdFromUuid($uuid) ?? $uuid;
        $newRole = $this->reform(new UpdatePermission, $data, $option, $param);
        $role    = $this->permissionRepository->update($id, $newRole, $option, $param);

        return $this->transform(new PermissionResource, $role, $option, $param);
    }

    /**
     * @param       $uuid
     * @param array $param
     */
    public function delete($uuid, array $param = []): void
    {
        $ids = explode(",", $uuid);

        foreach ($ids as $id) {
            $id = $this->permissionRepository->getIdFromUuid($id) ?? $id;

            $this->permissionRepository->delete($id);
        }
    }
}