<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Controllers\Api;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use iBoot\Models\UserModel;
use OpenApi\Annotations as OA;
use ReflectionException;

class User extends ResourceController
{
    /**
     * @OA\Get(
     *     path="/user",
     *     tags={"User"},
     *     summary="Find Users",
     *     description="Returns list of User objects",
     *     operationId="getUsers",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="data",type="array",@OA\Items(ref="#/components/schemas/User")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Access denied"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return an array of resource objects, themselves in array format
     */
    public function index()
    {
        $user = new UserModel();

        $data = $user->findAll();
        $data_num = count($data);
        for ($i = 0; $i < $data_num; $i++) {
            unset($data[$i]['password']);
        }

        return $this->respond($data, 200, count($data) . ' Users Found');
    }

    /**
     * @OA\Get(
     *     path="/user/{id}",
     *     tags={"User"},
     *     summary="Find User by ID",
     *     description="Returns a single User",
     *     operationId="getUserById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of User to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Access denied"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return the properties of a resource object
     *
     * @param mixed|null $id
     */
    public function show($id = null): Response
    {
        if (! is_numeric($id)) {
            return $this->failValidationErrors('Invalid id `' . $id . '`', null, 'Invalid id');
        }

        $user = new UserModel();

        $data = $user->where(['id' => $id])->first();

        if ($data) {
            $data_num = count($data);
            for ($i = 0; $i < $data_num; $i++) {
                unset($data[$i]['password']);
            }

            return $this->respond($data, 200, 'User with id ' . $id . ' Found');
        }

        return $this->failNotFound('No User Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/user",
     *     tags={"User"},
     *     summary="Add a new User",
     *     operationId="addUser",
     *     @OA\Response(
     *         response=201,
     *         description="Created User",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/User"}
     * )
     *
     * Create a new resource object, from "posted" parameters
     *
     * @throws ReflectionException
     */
    public function create()
    {
        $user = new UserModel();

        $data = [
            'name'     => $this->request->getVar('name'),
            'email'    => $this->request->getVar('email'),
            'phone'    => (empty($this->request->getVar('phone')) ? null : $this->request->getVar('phone')),
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password'),
        ];

        $user->insert($data);

        $id = $user->getInsertID();

        return $this->respondCreated($data, 'User Saved with id ' . $id);
    }

    /**
     * @OA\Put(
     *     path="/user/{id}",
     *     tags={"User"},
     *     summary="Update an existing User",
     *     operationId="updateUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Access denied"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/User"}
     * )
     * @OA\Post(
     *     path="/user/{id}",
     *     tags={"User"},
     *     summary="Update an existing User (Websafe alternative)",
     *     operationId="updateUserWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Access denied"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/User"}
     * )
     *
     * Add or update a model resource, from "posted" properties
     *
     * @param mixed|null $id
     *
     * @throws ReflectionException
     */
    public function update($id = null)
    {
        if (! empty($id) && ! is_numeric($id)) {
            return $this->failValidationErrors('Invalid id `' . $id . '`', null, 'Invalid id');
        }

        $userModel   = new UserModel();
        $userIsAdmin = session()->getFlashdata('userIsAdmin');
        $userID      = session()->getFlashdata('userID');

        if ($userIsAdmin || $id === $userID) {
            $user = $userModel->where('id', $id)->first();
            if (empty($user)) {
                return $this->failNotFound('No User Found with id ' . $id);
            }
            if (! empty($this->request->getVar('name')) && $user['name'] !== $this->request->getVar('name')) {
                $data['name'] = $this->request->getVar('name');
            }
            if (! empty($this->request->getVar('email')) && $user['email'] !== $this->request->getVar('email')) {
                $data['email'] = $this->request->getVar('email');
            }
            if ($user['phone'] !== $this->request->getVar('phone')) {
                $data['phone'] = (empty($this->request->getVar('phone')) ? null : $this->request->getVar('phone'));
            }
            if ($user['username'] !== $this->request->getVar('username')) {
                $data['username'] = $this->request->getVar('username');
            }
            if (! empty($this->request->getVar('password'))) {
                $data['password'] = $this->request->getVar('password');
            }

            if (! empty($data)) {
                if ($userModel->update($id, $data)) {
                    return $this->respondUpdated($data, 'User with id ' . $id . ' Updated');
                }

                return $this->respond($userModel->errors(), 401, 'Error Updating User with id ' . $id);
            }

            return $this->respond('Nothing to update');
        }

        return $this->respond(null, 401, 'Access denied');
    }

    /**
     * @OA\Delete(
     *     path="/user/{id}",
     *     tags={"User"},
     *     summary="Deletes a User",
     *     operationId="deleteUser",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Access denied"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     * @OA\Post(
     *     path="/user/{id}/delete",
     *     tags={"User"},
     *     summary="Deletes a User (Websafe alternative)",
     *     operationId="deleteUserWebsafe",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Access denied"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     *
     * Delete the designated resource object from the model
     *
     * @param mixed|null $id
     */
    public function delete($id = null)
    {
        if (! is_numeric($id)) {
            return $this->failValidationErrors('Invalid id `' . $id . '`', null, 'Invalid id');
        }

        $user        = new UserModel();
        $userIsAdmin = session()->getFlashdata('userIsAdmin');
        $userID      = session()->getFlashdata('userID');

        if ($userIsAdmin || $id === $userID) {
            $data = $user->find($id);

            if ($data) {
                $user->delete($id);

                log_message('info', 'User {username} was deleted by user with id {uid} using the API from {ip}', ['username' => $data['username'], 'uid' => $userID, 'ip' => $this->request->getIPAddress()]);

                return $this->respondDeleted(null, 'User with id ' . $id . ' Deleted');
            }

            return $this->failNotFound('No User Found with id ' . $id);
        }

        return $this->respond(null, 401, 'Access denied');
    }

    /**
     * @OA\Post(
     *     path="/user/login",
     *     tags={"User"},
     *     summary="Login with user credentials",
     *     operationId="userLogin",
     *     @OA\Response(
     *         response=200,
     *         description="Login Successful",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="token",type="string",description="User's API token"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"username", "password"},
     *                 @OA\Property(
     *                     property="username",
     *                     description="username or email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     * )
     *
     * Login with User credentials and receive API token
     */
    public function login(): Response
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return $this->failValidationErrors('Username or Password is empty');
        }

        $userModel = new UserModel();

        $user = $userModel->where('username', $username)->orWhere('email', $username)->first();

        if ($user === null || ! password_verify($password, $user['password'])) {
            return $this->failValidationErrors('Invalid credentials');
        }

        $token = \iBoot\Controllers\User::generateAPItoken($username);

        log_message('info', 'User {username} logged into the system using the API from {ip}', ['username' => $username, 'ip' => $this->request->getIPAddress()]);

        return $this->respond($token, 200, 'Login Successful');
    }
}
