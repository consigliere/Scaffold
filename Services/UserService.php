<?php
/**
 * UserService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/24/19 5:53 PM
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use App\Components\Scaffold\Services\User\Requests\CreateUser;
use App\Components\Scaffold\Services\User\Requests\UpdateUser;
use App\Components\Scaffold\Services\User\Responses\RoleCollection;
use App\Components\Scaffold\Services\User\Responses\UserCollection;
use App\Components\Scaffold\Services\User\Responses\UserResource;
use App\Components\Signature\Exceptions\BadRequestHttpException;
use App\Components\Signature\Exceptions\NotFoundHttpException;
use App\Components\Signature\Exceptions\UnprocessableEntityHttpException;
use Illuminate\Foundation\Application;

/**
 * Class UserService
 * @package App\Components\Scaffold\Services
 */
class UserService extends Service
{
    /**
     * @var \App\Components\Scaffold\Repositories\UserRepositoryInterface
     */
    private $userRepository;

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

    private $users;

    private $user;

    private $userId;

    private $roleIds;

    private $roleId;

    private $inputRoles;

    private $primaryRoles;

    private $additionalRoles;

    /**
     * UserService constructor.
     *
     * @param \Illuminate\Foundation\Application                            $app
     * @param \App\Components\Scaffold\Repositories\UserRepositoryInterface $userRepository
     * @param \App\Components\Scaffold\Repositories\RoleRepositoryInterface $roleRepository
     */
    public function __construct(Application $app, UserRepositoryInterface $userRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;

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
    public function profile(array $data = [], array $option = [], array $param = []): array
    {
        $user = $this->findUserById($this->auth->user()->id)->getUser();

        return (new UserResource)($user);
    }

    /**
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function browse(array $data = [], array $option = [], array $param = [])
    {
        $users = $this->findUsersPaging($data)->getUsers();

        return (new UserCollection)($users);
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
        $data['inList'] = $this->findRoleIds()->getRoleIds();
        $inputUsername  = data_get($data, 'input.username');
        $inputEmail     = data_get($data, 'input.email');
        $inputRoleId    = data_get($data, 'input.roleId') ?? null;

        $this->findUsersBy('username', $inputUsername)
            ->validateAndVerifyUsersIsAvailable('username', null, $inputUsername)->getUsers();
        $this->findUsersBy('email', $inputEmail)
            ->validateAndVerifyUsersIsAvailable('email', null, $inputEmail)->getUsers();

        if (null !== data_get($data, 'input.roleId')) {
            data_set($data, 'input.roleId', $this->findRoleIdByUuid($inputRoleId)->getRoleId());
        }

        $newUser = (new CreateUser)($data);
        $this->findUsersBy('uuid', $newUser['uuid'])->validateUsersUuidIsExistAndMessageTryAgain(null)->getUsers();

        $user = $this->userRepository->create($newUser);

        return (new UserResource)($user);
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function read($uuid, array $data = [], array $option = [], array $param = [])
    {
        $id   = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();
        $user = $this->findUserById($id)->validateUserIsExist(null, $uuid)->getUser();

        return (new UserResource)($user);
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function update($uuid, array $data, array $option = [], array $param = [])
    {
        $uid            = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();
        $data['inList'] = $this->findRoleIds()->getRoleId();
        $inputUsername  = data_get($data, 'input.username') ?? null;
        $inputEmail     = data_get($data, 'input.email') ?? null;
        $inputRoleId    = data_get($data, 'input.roleId') ?? null;

        if (null !== $inputUsername) {
            $this->findUsersBy('username', $inputUsername)
                ->validateAndVerifyUsersIsAvailable('username', null, $inputUsername, $uid)->getUsers();
        }

        if (null !== $inputEmail) {
            $this->findUsersBy('email', $inputEmail)
                ->validateAndVerifyUsersIsAvailable('email', null, $inputEmail, $uid)->getUsers();
        }

        if (null !== $inputRoleId) {
            data_set($data, 'input.roleId', $this->findRoleIdByUuid($inputRoleId)->getRoleId());
        }

        $newUser = (new UpdateUser)($data);
        $user    = $this->userRepository->update($uid, $newUser);

        return (new UserResource)($user);
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
            $uid = $this->findUserIdByUuid($id)->validateUserIdIsExist(null, $id)->getUserId();

            $this->userRepository->delete($uid);
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
    public function userRoles($uuid, array $data, array $option = [], array $param = [])
    {
        $uid = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();

        return $this->getUserRoles($uid);
    }

    /**
     * @param       $uuid
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return mixed
     */
    public function userAdditionalRoles($uuid, array $data, array $option = [], array $param = [])
    {
        $uid   = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();
        $roles = $this->findInputRoles($data)->validateInputRolesIsArray(null)->getInputRoles();
        $user  = $this->findUserById($uid)->getUser();

        if (isset($roles) && !empty($roles) && (null !== $roles)) {
            if ($option['type'] === 'sync') {
                $this->userRepository->detachUserRoles($uid);
            }

            foreach ($roles as $role) {
                $rid = $this->findRoleIdByUuid($role)->validateRoleIdIsExist(null, $role)->getRoleId();

                if ($option['type'] === 'add' || $option['type'] === 'remove') {
                    $userRoles = $this->findAdditionalRoles($uid)->getAdditionalRoles();
                    foreach ($userRoles as $userRole) {
                        if ($rid === $userRole->id) {
                            $this->userRepository->detachUserRoles($uid, $rid);
                        }
                    }
                }
                if ($option['type'] === 'add' || $option['type'] === 'sync') {
                    if ($user->role_id !== $rid) {
                        $this->userRepository->attachUserRoles($uid, $rid);
                    }
                }
            }
        }

        return $this->getUserRoles($uid);
    }

    /**
     * @param $userId
     *
     * @return mixed
     */
    private function getUserRoles($userId)
    {
        $primaryRole    = $this->findPrimaryRoles($userId)->getPrimaryRoles();
        $additionalRole = $this->findAdditionalRoles($userId)->getAdditionalRoles();

        return (new RoleCollection)($primaryRole, $additionalRole);
    }

    /**
     * @param $userId
     *
     * @return $this
     */
    private function findUserById($userId): self
    {
        $this->user = $this->userRepository->getById($userId);

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    private function findUsersPaging($data): self
    {
        $this->users = $this->userRepository->browse($data);

        return $this;
    }

    /**
     * @param $uuid
     *
     * @return $this
     */
    private function findUserIdByUuid($uuid): self
    {
        $this->userId = $this->userRepository->getIdbyUuid($uuid);

        return $this;
    }

    /**
     * @param        $type
     * @param        $value
     * @param string $operand
     * @param array  $arg
     *
     * @return $this
     */
    private function findUsersBy($type, $value, $operand = '=', array $arg = []): self
    {
        $val = $type === 'username' ? strtolower($value) : $value;

        $this->users = $this->userRepository->getWhere($type, $operand, $val);

        return $this;
    }

    /**
     * @return $this
     */
    private function findRoleIds(): self
    {
        $this->roleIds = $this->roleRepository->getIds();

        return $this;
    }

    /**
     * @param $uuid
     *
     * @return $this
     */
    private function findRoleIdByUuid($uuid): self
    {
        $this->roleId = $this->roleRepository->getIdbyUuid($uuid);

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    private function findInputRoles($data): self
    {
        $this->inputRoles = data_get($data, 'input.roles');

        return $this;
    }

    private function findPrimaryRoles($userId)
    {
        $this->primaryRoles = $this->userRepository->primaryRoles($userId);

        return $this;
    }

    private function findAdditionalRoles($userId)
    {
        $this->additionalRoles = $this->userRepository->additionalRoles($userId);

        return $this;
    }

    /**
     * @param null $id
     * @param      $uuid
     *
     * @return $this
     */
    private function validateUriQueryParam($id = null, $uuid): self
    {
        $newId = $id ?? $this->userId;

        if (null === $newId) {
            throw new NotFoundHttpException('Cannot find Users resources in URI query parameter /' . $uuid);
        }

        return $this;
    }

    /**
     * @param null $roleId
     * @param      $uuid
     *
     * @return $this
     */
    private function validateRoleIdIsExist($roleId = null, $uuid): self
    {
        $rid = $roleId ?? $this->roleId;

        if (null === $rid) {
            throw new BadRequestHttpException('Cannot find Role with ID #' . $uuid);
        }

        return $this;
    }

    /**
     * @param null $userId
     * @param      $uuid
     *
     * @return $this
     */
    private function validateUserIdIsExist($userId = null, $uuid): self
    {
        $uid = $userId ?? $this->userId;

        if (null === $uid) {
            throw new BadRequestHttpException('Cannot find User with ID #' . $uuid);
        }

        return $this;
    }

    /**
     * @param null $user
     * @param      $uuid
     *
     * @return $this
     */
    private function validateUserIsExist($user = null, $uuid): self
    {
        $newUser = $user ?? $this->user;

        if (null === $newUser) {
            throw new BadRequestHttpException('Cannot find User with ID #' . $uuid);
        }

        return $this;
    }

    /**
     * @param null $users
     *
     * @return $this
     */
    private function validateUsersUuidIsExistAndMessageTryAgain($users = null): self
    {
        $newUsers = $users ?? $this->users;

        if ($newUsers->isNotEmpty()) {
            throw new UnprocessableEntityHttpException('Please try again');
        }

        return $this;
    }

    /**
     * @param      $type
     * @param null $users
     * @param      $value
     * @param null $userId
     *
     * @return $this
     */
    private function validateAndVerifyUsersIsAvailable($type, $users = null, $value, $userId = null): self
    {
        $newUsers = $users ?? $this->users;
        $message  = ucfirst($type) . " $value already exists, please try another";

        if (null === $userId) {
            if ($newUsers->isNotEmpty()) {
                throw new UnprocessableEntityHttpException($message);
            }
        } else {
            if ($newUsers->isNotEmpty()) {
                foreach ($newUsers as $user) {
                    if ($user->id !== $userId) {
                        throw new UnprocessableEntityHttpException($message);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param null $inputRoles
     *
     * @return $this
     */
    private function validateInputRolesIsArray($inputRoles = null): self
    {
        $newInputRoles = $inputRoles ?? $this->inputRoles;

        if (!is_array($newInputRoles)) {
            throw new BadRequestHttpException('Roles expected to be an array');
        }

        return $this;
    }
}