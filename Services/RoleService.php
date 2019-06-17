<?php
/**
 * RoleService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/17/19 3:35 PM
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use App\Components\Scaffold\Services\Role\Requests\CreateRole;
use App\Components\Scaffold\Services\Role\Requests\UpdateRole;
use App\Components\Scaffold\Services\Role\Responses\RoleCollection;
use App\Components\Scaffold\Services\Role\Responses\RoleResource;
use App\Components\Signature\Exceptions\BadRequestHttpException;
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

    /**
     * @var mixed
     */
    private $request;

    /**
     * RoleService constructor.
     *
     * @param \Illuminate\Foundation\Application                            $app
     * @param \App\Components\Scaffold\Repositories\RoleRepositoryInterface $RoleRepository
     */
    public function __construct(Application $app, RoleRepositoryInterface $RoleRepository)
    {
        $this->roleRepository = $RoleRepository;

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
        $roles = $this->roleRepository->browse($data);

        return (new RoleCollection)($roles);
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
        $name     = data_get($data, 'input.name');
        $roleName = $this->roleRepository->getWhere('name', $name);

        if ($roleName->isNotEmpty()) {
            throw new UnprocessableEntityHttpException("Role name $name already exists, please try another");
        }

        $newRole = (new CreateRole)($data);
        $role    = $this->roleRepository->create($newRole);

        return (new RoleResource)($role);
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
        $id = $this->roleRepository->getIdbyUuid($uuid);

        if (null === $id) {
            throw new NotFoundHttpException('Cannot find Roles resources in URI query parameter /' . $uuid);
        }

        $role = $this->roleRepository->getById($id);

        if (null === $role) {
            throw new BadRequestHttpException('Cannot find Role with ID #' . $uuid);
        }

        return (new RoleResource)($role);
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
        $id   = $this->roleRepository->getIdbyUuid($uuid);
        $name = data_get($data, 'input.name');

        if (null === $id) {
            throw new NotFoundHttpException('Cannot find Roles resources in URI query parameter /' . $uuid);
        }

        if (null !== data_get($data, 'input.name')) {
            $roles = $this->roleRepository->getWhere('name', strtolower($name));

            if ($roles->isNotEmpty()) {
                foreach ($roles as $role) {
                    if ($role->id !== $id) {
                        throw new UnprocessableEntityHttpException("Role name $name already exists, please try another");
                    }
                }
            }
        }

        $newRole = (new UpdateRole)($data);
        $role    = $this->roleRepository->update($id, $newRole);

        return (new RoleResource)($role);
    }

    /**
     * @param       $uuid
     * @param array $param
     */
    public function delete($uuid, array $param = []): void
    {
        $ids = explode(',', $uuid);

        foreach ($ids as $id) {
            $id = $this->roleRepository->getIdbyUuid($id);

            if (null === $id) {
                throw new BadRequestHttpException('Cannot find Role with ID #' . $uuid);
            }

            $this->roleRepository->delete($id);
        }
    }
}