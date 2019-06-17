<?php
/**
 * UserService.php
 * Created by @anonymoussc on 04/08/2019 11:44 PM.
 */

/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 6/17/19 4:44 PM
 */

namespace App\Components\Scaffold\Services;

use App\Components\Scaffold\Repositories\RoleRepositoryInterface;
use App\Components\Scaffold\Repositories\UserRepositoryInterface;
use App\Components\Scaffold\Services\User\Requests\CreateUser;
use App\Components\Scaffold\Services\User\Requests\UpdateUser;
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
     * @param array $data
     * @param array $option
     * @param array $param
     *
     * @return array
     */
    public function profile(array $data = [], array $option = [], array $param = []): array
    {
        $user = $this->userRepository->getById($this->auth->user()->id);

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
        $users = $this->userRepository->browse($data);

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
        $data['inList'] = $this->roleRepository->getIds();
        $username       = data_get($data, 'input.username');
        $userusername   = $this->userRepository->getWhere('username', strtolower($username));

        if ($userusername->isNotEmpty()) {
            throw new UnprocessableEntityHttpException("Username $username already exists, please try another");
        }

        $email     = data_get($data, 'input.email');
        $useremail = $this->userRepository->getWhere('email', $email);

        if ($useremail->isNotEmpty()) {
            throw new UnprocessableEntityHttpException("Email $email already exists, please try another");
        }

        if (null !== data_get($data, 'input.roleId')) {
            data_set($data, 'input.roleId', $this->roleRepository->getIdbyUuid(data_get($data, 'input.roleId')));
        }

        $newUser  = (new CreateUser)($data);
        $useruuid = $this->userRepository->getWhere('uuid', $newUser['uuid']);

        if ($useruuid->isNotEmpty()) {
            throw new UnprocessableEntityHttpException('Please try again');
        }

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
        $id = $this->userRepository->getIdbyUuid($uuid);

        if (null === $id) {
            throw new NotFoundHttpException('Cannot find Users resources in URI query parameter /' . $uuid);
        }

        $user = $this->userRepository->getById($id);

        if (null === $user) {
            throw new BadRequestHttpException('Cannot find User with ID #' . $uuid);
        }

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
        $data['inList'] = $this->roleRepository->getIds();
        $id             = $this->userRepository->getIdbyUuid($uuid);

        if (null === $id) {
            throw new NotFoundHttpException('Cannot find Users resources in URI query parameter /' . $uuid);
        }

        if (null !== data_get($data, 'input.username')) {
            $username = data_get($data, 'input.username');
            $users    = $this->userRepository->getWhere('username', strtolower($username));

            if ($users->isNotEmpty()) {
                foreach ($users as $user) {
                    if ($user->id !== $id) {
                        throw new UnprocessableEntityHttpException("Username $username already exists, please try another");
                    }
                }
            }
        }

        if (null !== data_get($data, 'input.email')) {
            $email = data_get($data, 'input.email');
            $users = $this->userRepository->getWhere('email', $email);

            if ($users->isNotEmpty()) {
                foreach ($users as $user) {
                    if ($user->id !== $id) {
                        throw new UnprocessableEntityHttpException("Email address $email already exists, please try another");
                    }
                }
            }
        }

        if (null !== data_get($data, 'input.roleId')) {
            data_set($data, 'input.roleId', $this->roleRepository->getIdbyUuid(data_get($data, 'input.roleId')));
        }

        $newUser = (new UpdateUser)($data);
        $user    = $this->userRepository->update($id, $newUser);

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
            $uid = $this->userRepository->getIdbyUuid($id);

            if (null === $uid) {
                throw new BadRequestHttpException('Cannot find User with ID #' . $id);
            }

            $this->userRepository->delete($uid);
        }
    }
}