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

use CodeIgniter\HTTP\ResponseInterface;
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

        $data     = $user->findAll();
        $data_num = count($data);

        for ($i = 0; $i < $data_num; $i++) {
            unset($data[$i]->password);
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
    public function show($id = null): ResponseInterface
    {
        if (! is_numeric($id)) {
            return $this->failValidationErrors('Invalid id `' . $id . '`', null, 'Invalid id');
        }

        $user = new UserModel();

        $data = $user->where(['id' => $id])->first();

        if ($data) {
            unset($data->password);

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

        $userIsAdmin = session()->getFlashdata('userIsAdmin');

        if (! $userIsAdmin) {
            return $this->respond(null, 401, 'Access denied');
        }

        $data = [
            'name'          => $this->request->getVar('name'),
            'email'         => $this->request->getVar('email'),
            'phone'         => (empty($this->request->getVar('phone')) ? null : $this->request->getVar('phone')),
            'username'      => $this->request->getVar('username'),
            'password'      => $this->request->getVar('password'),
            'verifiedEmail' => (empty($this->request->getVar('verifiedEmail')) ? 0 : (int) $this->request->getVar('verifiedEmail')),
            'isAdmin'       => (empty($this->request->getVar('isAdmin')) ? 0 : (int) $this->request->getVar('isAdmin')),
            'labs'          => (empty($this->request->getVar('labs')) ? null : $this->request->getVar('labs')),
        ];

        if ($user->save($data)) {
            log_message('notice', 'Created user ' . $data['username'] . 'with id ' . $user->getInsertID());

            return $this->respondCreated($data, 'User Saved with id ' . $user->getInsertID());
        }

        log_message('warning', 'Failed to create user ' . $data['username'] . "\n" . var_export($user->errors(), true));

        return $this->fail('Failed to create user. Errors: ' . json_encode($user->errors()));
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

        if (! $userIsAdmin && $id !== $userID) {
            return $this->respond(null, 401, 'Access denied');
        }

        $user = $userModel->where('id', $id)->first();
        if (empty($user)) {
            return $this->failNotFound('No User Found with id ' . $id);
        }
        if ($this->request->getVar('name') !== null && $user->name !== $this->request->getVar('name')) {
            $data['name'] = $this->request->getVar('name');
        }
        if ($this->request->getVar('email') !== null && $user->email !== $this->request->getVar('email')) {
            $data['email'] = $this->request->getVar('email');
        }
        if ($this->request->getVar('phone') !== null && $user->phone !== $this->request->getVar('phone')) {
            $data['phone'] = $this->request->getVar('phone');
        }
        if ($this->request->getVar('username') !== null && $user->username !== $this->request->getVar('username')) {
            $data['username'] = $this->request->getVar('username');
        }
        if ($this->request->getVar('password') !== null) {
            $data['password'] = $this->request->getVar('password');
        }
        if ($this->request->getVar('verifiedEmail') !== null && $user->verifiedEmail !== (int) $this->request->getVar('verifiedEmail')) {
            $data['verifiedEmail'] = (int) $this->request->getVar('verifiedEmail');
        }
        if ($this->request->getVar('isAdmin') !== null && $user->isAdmin !== (int) $this->request->getVar('isAdmin')) {
            $data['isAdmin'] = (int) $this->request->getVar('isAdmin');
        }
        if ($this->request->getVar('labs') !== null) {
            $data['labs']        = $this->request->getVar('labs');
            $data['userIsAdmin'] = $userIsAdmin;
        }

        if (! empty($data)) {
            if ($userModel->update($id, $data)) {
                return $this->respondUpdated($data, 'User with id ' . $id . ' Updated');
            }

            return $this->respond($userModel->errors(), 401, 'Error Updating User with id ' . $id);
        }

        return $this->respond('Nothing to update');
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

                log_message('info', 'User {username} was deleted by user with id {uid} using the API from {ip}', ['username' => $data->username, 'uid' => $userID, 'ip' => $this->request->getIPAddress()]);

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
    public function login(): ResponseInterface
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return $this->failValidationErrors('Username or Password is empty');
        }

        $userModel = new UserModel();

        $user = $userModel->where('username', $username)->orWhere('email', $username)->first();

        if ($user === null || ! password_verify($password, $user->password)) {
            return $this->failValidationErrors('Invalid credentials');
        }

        $token = \iBoot\Controllers\User::generateAPItoken($username);

        log_message('info', 'User {username} logged into the system using the API from {ip}', ['username' => $username, 'ip' => $this->request->getIPAddress()]);

        return $this->respond($token, 200, 'Login Successful');
    }
}
