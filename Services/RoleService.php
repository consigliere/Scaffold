<?php
/**
 * RoleService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/9/19 1:34 AM
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\PermissionRepositoryInterface;
use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use App\Components\Scaffold\Services\Role\Requests\CreateRole;
use App\Components\Scaffold\Services\Role\Requests\UpdateRole;
use App\Components\Scaffold\Services\Role\Responses\PermissionCollection;
use App\Components\Scaffold\Services\Role\Responses\RelatedPermissionCollection;
use App\Components\Scaffold\Services\Role\Responses\RoleCollection;
use App\Components\Scaffold\Services\Role\Responses\RolePermissionsResource;
use App\Components\Scaffold\Services\Role\Responses\RoleResource;
use App\Components\Signature\Exceptions\BadRequestHttpException;
use App\Components\Signature\Exceptions\ConflictHttpException;
use App\Components\Signature\Exceptions\NotFoundHttpException;
use App\Components\Signature\Exceptions\UnprocessableEntityHttpException;
use Illuminate\Foundation\Application;

/**
 * Class RoleService
 * @package App\Components\Scaffold\Services
 */
class RoleService extends Service
{
    /**
     * @var \App\Components\Scaffold\Repositories\RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * @var \Illuminate\Auth\AuthManager|mixed
     */
    private $auth;

    private $permissionRepository;

    /**
     * @var mixed
     */
    private $request;
    private $roles;
    private $roleId;
    private $permissions;
    private $role;
    private $inputPermissions;
    private $permissionId;

    /**
     * RoleService constructor.
     *
     * @param \Illuminate\Foundation\Application                                  $app
     * @param \App\Components\Scaffold\Repositories\RoleRepositoryInterface       $RoleRepository
     * @param \App\Components\Scaffold\Repositories\PermissionRepositoryInterface $PermissionRepository
     */
    public function __construct(Application $app, RoleRepositoryInterface $RoleRepository, PermissionRepositoryInterface $PermissionRepository)
    {
        $this->roleRepository       = $RoleRepository;
        $this->permissionRepository = $PermissionRepository;

        $this->auth    = $app->make('auth');
        $this->request = $app->make('request');
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $prop = lcfirst(substr($name, 3));

        return $this->$prop;
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
        return (new RoleCollection)(
            $this->findRolesPaging($data)->getRoles()
        );
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
        $inputName = data_get($data, 'input.name');
        $roles     = $this->roleRepository->getWhere('name', $inputName);

        if ($roles->isNotEmpty()) {
            throw new ConflictHttpException("Role name $inputName already exists, please try another");
        }

        $newRole = (new CreateRole)($data);
        $role    = $this->roleRepository->create($newRole);

        if (!$role) {
            return (new RoleResource)($role);
        } else {
            $rid = $role->id;
        }

        return (new RolePermissionsResource)(
            $this->findRoleFirstById($rid)->validateRoleIsExist(null, null)->getRole()
        );
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
        $rid = $this->findRoleIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getRoleId();

        return (new RolePermissionsResource)(
            $this->findRoleFirstById($rid)->validateRoleIsExist(null, $uuid)->getRole()
        );
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
        $roleId    = $this->roleRepository->getIdbyUuid($uuid);
        $inputName = data_get($data, 'input.name');

        if (null === $roleId) {
            throw new NotFoundHttpException('Cannot find Roles resources in URI query parameter /' . $uuid);
        }

        if (null !== data_get($data, 'input.name')) {
            $roles = $this->roleRepository->getWhere('name', strtolower($inputName));

            if ($roles->isNotEmpty()) {
                foreach ($roles as $role) {
                    if ($role->id !== $roleId) {
                        throw new ConflictHttpException("Role name $inputName already exists, please try another");
                    }
                }
            }
        }

        $newRole = (new UpdateRole)($data);
        $role    = $this->roleRepository->update($roleId, $newRole);

        if (!$role) {
            return (new RoleResource)($role);
        } else {
            $rid = $role->id;
        }

        return (new RolePermissionsResource)(
            $this->findRoleFirstById($rid)->validateRoleIsExist(null, null)->getRole()
        );
    }

    /**
     * @param       $uuid
     * @param array $param
     */
    public function delete($uuid, array $param = []): void
    {
        $trimmed = rtrim(trim(preg_replace('/\s+/', '', $uuid)), ',');
        $ids     = explode(',', $trimmed);

        foreach ($ids as $id) {
            $rid = $this->roleRepository->getIdbyUuid($id);

            if (null === $rid) {
                throw new BadRequestHttpException('Cannot find Role with ID #' . $id);
            }

            $this->roleRepository->delete($rid);
        }
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function relatedPermissions($uuid, array $data, array $option = [], array $param = [])
    {
        $rid = $this->findRoleIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getRoleId();

        return (new RelatedPermissionCollection)(
            $this->findPermissionsByRole($rid)->getPermissions()
        );
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function rolePermissions($uuid, array $data, array $option = [], array $param = [])
    {
        $rid = $this->findRoleIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getRoleId();

        return (new PermissionCollection)(
            $this->findPermissionsByRole($rid)->getPermissions()
        );
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function permissionAction($uuid, array $data, array $option = [], array $param = [])
    {
        $rid              = $this->findRoleIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getRoleId();
        $inputPermissions = $this->findInputPermissions($data)->validateInputPermissionsIsArray(null)->getInputPermissions();

        if (isset($inputPermissions) && !empty($inputPermissions) && (null !== $inputPermissions)) {
            if ($option['type'] === 'sync') {
                $this->roleRepository->detachPermissions($rid);
            }

            foreach ($inputPermissions as $permission) {
                $pid = $this->findPermissionIdByUuid($permission)->validatePermissionIdIsExist(null, $permission)->getPermissionId();

                if ($option['type'] === 'add' || $option['type'] === 'remove') {
                    $rolePermissions = $this->findPermissionsByRole($rid)->getPermissions();
                    foreach ($rolePermissions as $rp) {
                        if ($pid === $rp->id) {
                            $this->roleRepository->detachPermissions($rid, $pid);
                        }
                    }
                }
                if ($option['type'] === 'add' || $option['type'] === 'sync') {
                    $this->roleRepository->attachPermissions($rid, $pid);
                }
            }
        }

        return (new PermissionCollection)(
            $this->findPermissionsByRole($rid)->getPermissions()
        );
    }

    /**
     * @param $data
     *
     * @return \App\Components\Scaffold\Services\RoleService
     */
    private function findInputPermissions($data): self
    {
        if (!array_key_exists('permissions', $data['input'])) {
            throw new UnprocessableEntityHttpException('Permissions key are required');
        }

        $this->inputPermissions = data_get($data, 'input.permissions');

        return $this;
    }

    /**
     * @param null $inputRoles
     *
     * @return \App\Components\Scaffold\Services\RoleService
     */
    private function validateInputPermissionsIsArray($inputRoles = null): self
    {
        $newInputPermissions = $inputRoles ?? $this->inputPermissions;

        if (!is_array($newInputPermissions)) {
            throw new BadRequestHttpException('Permissions expected to be an array');
        }

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    private function findRolesPaging($data): self
    {
        $this->roles = $this->roleRepository->browse($data);

        return $this;
    }

    /**
     * @param $uuid
     *
     * @return \App\Components\Scaffold\Services\RoleService
     */
    private function findRoleIdByUuid($uuid): self
    {
        $this->roleId = $this->roleRepository->getIdbyUuid($uuid);

        return $this;
    }

    private function findPermissionIdByUuid($uuid): self
    {
        $this->permissionId = $this->permissionRepository->getIdbyUuid($uuid);

        return $this;
    }

    /**
     * @param $roleId
     *
     * @return $this
     */
    private function findPermissionsByRole($roleId): self
    {
        $this->permissions = $this->roleRepository->permissions($roleId);

        return $this;
    }

    /**
     * @param $roleId
     *
     * @return \App\Components\Scaffold\Services\RoleService
     */
    private function findRoleFirstById($roleId): self
    {
        $this->role = $this->roleRepository->firstById($roleId);

        return $this;
    }

    /**
     * @param null $role
     * @param null $uuid
     *
     * @return \App\Components\Scaffold\Services\RoleService
     */
    private function validateRoleIsExist($role = null, $uuid = null): self
    {
        $newRole = $role ?? $this->role;

        if (null === $newRole) {
            if (null === $uuid) {
                throw new BadRequestHttpException('Cannot find Role');
            }

            throw new BadRequestHttpException('Cannot find Role with ID #' . $uuid);
        }

        return $this;
    }

    /**
     * @param null $id
     * @param null $uuid
     *
     * @return \App\Components\Scaffold\Services\RoleService
     */
    private function validateUriQueryParam($id = null, $uuid = null): self
    {
        $newId = $id ?? $this->roleId;

        if (null === $newId) {
            if (null === $uuid) {
                throw new NotFoundHttpException('Cannot find Roles resources in URI query parameter');
            }

            throw new NotFoundHttpException('Cannot find Roles resources in URI query parameter /' . $uuid);
        }

        return $this;
    }

    private function validatePermissionIdIsExist($permissionId = null, $uuid = null): self
    {
        $pid = $permissionId ?? $this->permissionId;

        if (null === $pid) {
            if (null === $uuid) {
                throw new BadRequestHttpException('Cannot find Role ID');
            }

            throw new BadRequestHttpException('Cannot find Role with ID #' . $uuid);
        }

        return $this;
    }
}