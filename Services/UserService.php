<?php
/**
 * UserService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 7/2/19 7:23 AM
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use App\Components\Scaffold\Services\User\Requests\CreateUser;
use App\Components\Scaffold\Services\User\Requests\UpdateUser;
use App\Components\Scaffold\Services\User\Responses\AdditionalRolesCollection;
use App\Components\Scaffold\Services\User\Responses\PrimaryRoleResource;
use App\Components\Scaffold\Services\User\Responses\RelatedAdditionalRolesCollection;
use App\Components\Scaffold\Services\User\Responses\RelatedPrimaryRoleResource;
use App\Components\Scaffold\Services\User\Responses\UserCollection;
use App\Components\Scaffold\Services\User\Responses\UserResource;
use App\Components\Scaffold\Services\User\Responses\UserRolesResource;
use App\Components\Signature\Exceptions\BadRequestHttpException;
use App\Components\Signature\Exceptions\ConflictHttpException;
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
        $this->bootsJsonApi();

        return (new UserRolesResource)(
            $this->findUserFirstById($this->auth->user()->id)->getUser()
        );
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
        $this->bootsJsonApi();

        return (new UserCollection)(
            $this->findUsersPaging($data)->getUsers()
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
        $this->bootsJsonApi();

        $data['inList'] = $this->findRoleIds()->getRoleIds();
        $this->preCreateUpdate($data);

        if (null !== data_get($data, 'input.roleId')) {
            $inputRoleId = data_get($data, 'input.roleId');
            data_set($data, 'input.roleId', $this->findRoleIdByUuid($inputRoleId)->getRoleId());
        }

        $newUser = (new CreateUser)($data);
        $this->findUsersBy('uuid', $newUser['uuid'])->verifyUsersUuidAlreadyExist();

        $freshmen = $this->userRepository->create($newUser);

        if (!$freshmen) {
            return (new UserResource)(
                $freshmen
            );
        } else {
            $uid = $freshmen->id;
        }

        return (new UserRolesResource)(
            $this->findUserFirstById($uid)->validateUserIsExist(null, null)->getUser()
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
    public function read($uuid, array $data = [], array $option = [], array $param = [])
    {
        $this->bootsJsonApi();

        $id = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();

        return (new UserRolesResource)(
            $this->findUserFirstById($id)->validateUserIsExist(null, $uuid)->getUser()
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
    public function update($uuid, array $data, array $option = [], array $param = [])
    {
        $this->bootsJsonApi();

        $uid            = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();
        $data['inList'] = $this->findRoleIds()->getRoleIds();
        $this->preCreateUpdate($data, $uid);

        if (null !== data_get($data, 'input.roleId')) {
            $inputRoleId = data_get($data, 'input.roleId');
            data_set($data, 'input.roleId', $this->findRoleIdByUuid($inputRoleId)->getRoleId());
        }

        $newUser = (new UpdateUser)($data);

        $freshmen = $this->userRepository->update($uid, $newUser);

        if (!$freshmen) {
            return (new UserResource)(
                $freshmen
            );
        } else {
            $uid = $freshmen->id;
        }

        return (new UserRolesResource)(
            $this->findUserFirstById($uid)->validateUserIsExist(null, null)->getUser()
        );
    }

    /**
     * @param       $uuid
     * @param array $param
     */
    public function delete($uuid, array $param = []): void
    {
        $this->bootsJsonApi();

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
    public function relatedPrimaryRole($uuid, array $data, array $option = [], array $param = [])
    {
        $this->bootsJsonApi();

        $uid = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();

        return (new RelatedPrimaryRoleResource)(
            $this->findPrimaryRoles($uid)->getPrimaryRoles()
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
    public function userPrimaryRole($uuid, array $data, array $option = [], array $param = [])
    {
        $this->bootsJsonApi();

        $uid = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();

        return (new PrimaryRoleResource)(
            $this->findPrimaryRoles($uid)->getPrimaryRoles()
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
    public function relatedAdditionalRoles($uuid, array $data, array $option = [], array $param = [])
    {
        $this->bootsJsonApi();

        $uid = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();

        return (new RelatedAdditionalRolesCollection)(
            $this->findAdditionalRoles($uid)->getAdditionalRoles()
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
    public function userAdditionalRoles($uuid, array $data, array $option = [], array $param = [])
    {
        $this->bootsJsonApi();

        $uid = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();

        return (new AdditionalRolesCollection)(
            $this->findAdditionalRoles($uid)->getAdditionalRoles()
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
    public function operationAdditionalRoles($uuid, array $data, array $option = [], array $param = [])
    {
        $this->bootsJsonApi();

        $uid        = $this->findUserIdByUuid($uuid)->validateUriQueryParam(null, $uuid)->getUserId();
        $inputRoles = $this->findInputRoles($data)->validateInputRolesIsArray(null)->getInputRoles();
        $user       = $this->findUserById($uid)->getUser();

        if (isset($inputRoles) && !empty($inputRoles) && (null !== $inputRoles)) {
            if ($option['type'] === 'sync') {
                $this->userRepository->detachUserRoles($uid);
            }

            foreach ($inputRoles as $role) {
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

        return (new AdditionalRolesCollection)(
            $this->findAdditionalRoles($uid)->getAdditionalRoles()
        );
    }

    /**
     * @param      $data
     * @param null $userId
     */
    private function preCreateUpdate($data, $userId = null): void
    {
        $username = data_get($data, 'input.username');
        $email    = data_get($data, 'input.email');
        $uid      = $userId ?? null;

        if (null !== data_get($data, 'input.username')) {
            $this->findUsersBy('username', $username)->verifyUsersIsAvailable('username', null, $username, $uid);
        }

        if (null !== data_get($data, 'input.email')) {
            $this->findUsersBy('email', $email)->verifyUsersIsAvailable('email', null, $email, $uid);
        }
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
     * @param $userId
     *
     * @return $this
     */
    private function findUserFirstById($userId): self
    {
        $this->user = $this->userRepository->firstById($userId);

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
     * @param array  $arg
     *
     * @return $this
     */
    private function findUsersBy($type, $value, array $arg = []): self
    {
        $val = $type === 'username' ? strtolower($value) : $value;

        $this->users = $this->userRepository->getWhere($type, $val);

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

    /**
     * @param $userId
     *
     * @return $this
     */
    private function findPrimaryRoles($userId): self
    {
        $this->primaryRoles = $this->userRepository->primaryRoles($userId);

        return $this;
    }

    /**
     * @param $userId
     *
     * @return $this
     */
    private function findAdditionalRoles($userId): self
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
    private function validateUriQueryParam($id = null, $uuid = null): self
    {
        $newId = $id ?? $this->userId;

        if (null === $newId) {
            if (null === $uuid) {
                throw new NotFoundHttpException('Cannot find Users resources in URI query parameter');
            }

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
    private function validateRoleIdIsExist($roleId = null, $uuid = null): self
    {
        $rid = $roleId ?? $this->roleId;

        if (null === $rid) {
            if (null === $uuid) {
                throw new BadRequestHttpException('Cannot find Role ID');
            }

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
    private function validateUserIdIsExist($userId = null, $uuid = null): self
    {
        $uid = $userId ?? $this->userId;

        if (null === $uid) {
            if (null === $uuid) {
                throw new BadRequestHttpException('Cannot find User ID');
            }

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
    private function validateUserIsExist($user = null, $uuid = null): self
    {
        $newUser = $user ?? $this->user;

        if (null === $newUser) {
            if (null === $uuid) {
                throw new BadRequestHttpException('Cannot find User');
            }

            throw new BadRequestHttpException('Cannot find User with ID #' . $uuid);
        }

        return $this;
    }

    /**
     * @param null $users
     *
     * @return $this
     */
    private function verifyUsersUuidAlreadyExist($users = null): self
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
    private function verifyUsersIsAvailable($type, $users = null, $value, $userId = null): self
    {
        $newUsers = $users ?? $this->users;
        $message  = ucfirst($type) . " $value already exists, please try another";

        if (null === $userId) {
            if ($newUsers->isNotEmpty()) {
                throw new ConflictHttpException($message);
            }
        } else {
            if ($newUsers->isNotEmpty()) {
                foreach ($newUsers as $user) {
                    if ($user->id !== $userId) {
                        throw new ConflictHttpException($message);
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