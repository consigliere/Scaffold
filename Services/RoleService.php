<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/12/19 2:38 AM
 */

/**
 * RoleService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use App\Components\Scaffold\Services\Role\Requests\CreateRole;
use App\Components\Scaffold\Services\Role\Requests\UpdateRole;
use App\Components\Scaffold\Services\Role\Responses\RoleCollection;
use App\Components\Scaffold\Services\Role\Responses\RoleResource;
use Illuminate\Foundation\Application;

class RoleService extends Service
{
    private $roleRepository;

    public function __construct(Application $app, RoleRepositoryInterface $RoleRepository)
    {
        $this->roleRepository = $RoleRepository;
    }

    public function browse(array $data = [], array $option = [], array $param = []): array
    {
        $roles = $this->roleRepository->browse($data);

        return $this->transform(new RoleCollection, $roles, $option, $param);
    }

    public function create(array $data, array $option = [], array $param = []): array
    {
        $newRole = $this->reform(new CreateRole, $data);
        $role    = $this->roleRepository->create($newRole, $option, $param);

        return $this->transform(new RoleResource, $role, $option, $param);
    }

    public function read($uuid, array $data, array $option = [], array $param = [])
    {
        $id   = $this->roleRepository->getIdFromUuid($uuid) ?? $uuid;
        $role = $this->roleRepository->getById($id);

        return $this->transform(new RoleResource, $role, $option, $param);
    }

    public function update($uuid, array $data, array $option = [], array $param = []): array
    {
        $id      = $this->roleRepository->getIdFromUuid($uuid) ?? $uuid;
        $newRole = $this->reform(new UpdateRole, $data, $option, $param);
        $role    = $this->roleRepository->update($id, $newRole, $option, $param);

        return $this->transform(new RoleResource, $role, $option, $param);
    }

    public function delete($uuid, array $param = []): void
    {
        $ids = explode(",", $uuid);

        foreach ($ids as $id) {
            $id = $this->roleRepository->getIdFromUuid($id) ?? $id;

            $this->roleRepository->delete($id);
        }
    }
}