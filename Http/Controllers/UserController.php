<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 4/25/19 5:26 AM
 */

/**
 * UserController.php
 * Created by @anonymoussc on 04/08/2019 11:35 PM.
 */

namespace App\Components\Scaffold\Http\Controllers;

use App\Components\Scaffold\Http\Requests\{
    UserCreateFormRequest, UserUpdateFormRequest
};
use App\Components\Scaffold\Services\UserService;

class UserController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function browse()
    {

    }

    public function create(UserCreateFormRequest $request)
    {
        try {
            $data   = $request->all();
            $option = [];

            $param['self']['link'] = $request->fullUrl();

            $response = $this->userService->create($data, $option, $param);
        } catch (\Exception $error) {
            $this->fireLog('error', $error->getMessage(), ['error' => $error]);

            return response()
                ->error($error->getMessage(), $error->getCode())
                ->setStatusCode(500);
        }

        /*$records = (!$data)
            ? response()->fail(['result' => $data, 'message' => 'Failed create User data',])
            : response()->success(
                [
                    'result'  => $data,
                    'message' => 'User data has been successfully created',
                    'link'    => '',
                ]
            );*/

        return $this->response($response);
    }

    public function read()
    {
    }

    public function update($id, UserUpdateFormRequest $request)
    {

    }

    public function delete()
    {
    }
}
