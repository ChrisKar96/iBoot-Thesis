<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use iBoot\Models\UserModel;
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
     *         response=404,
     *         description="User objects not found"
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

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => count($data) . ' Users Found',
            'data'     => $data,
        ];

        return $this->respond($response);
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
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     *
     * Return the properties of a resource object
     *
     * @param mixed|null $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $user = new UserModel();

        $data = $user->where(['id' => $id])->first();

        if ($data) {
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'User with id ' . $id . ' Found',
                'data'     => $data,
            ];

            return $this->respond($response);
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
     *@throws ReflectionException
     */
    public function create()
    {
        $user = new UserModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $user->insert($data);

        $id = $user->getInsertID();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'User Saved with id ' . $id,
        ];

        return $this->respondCreated($response);
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
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     requestBody={"$ref": "#/components/requestBodies/User"}
     * )
     *
     * @OA\Post(
     *     path="/user/update/{id}",
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
     *@throws ReflectionException
     */
    public function update($id = null)
    {
        $user = new UserModel();

        $data = [
            'name' => $this->request->getVar('name'),
        ];

        $user->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'User with id ' . $id . ' Updated',
        ];

        return $this->respondUpdated($response);
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
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     *
     * @OA\Post(
     *     path="/user/delete/{id}",
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
        $user = new UserModel();

        $data = $user->find($id);

        if ($data) {
            $user->delete($id);

            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => 'User with id ' . $id . ' Deleted',
            ];

            return $this->respondDeleted($response);
        }

        return $this->failNotFound('No User Found with id ' . $id);
    }

    /**
     * @OA\Post(
     *     path="/user/login",
     *     tags={"User"},
     *     summary="Login with user credentials",
     *     operationId="userLogin",
     *     @OA\Response(
     *         response=200,
     *         description="User Logged In",
     *         @OA\JsonContent(type="object",
     *            @OA\Property(property="token",type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *         			   description="username or email",
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
     *
     * @param mixed|null $username
     * @param mixed|null $password
     */
    public function login($username = null, $password = null)
    {
        $userModel = new UserModel();

        if (empty($username)) {
            $username = $this->request->getVar('username');
        }
        if (empty($password)) {
            $password = $this->request->getVar('password');
        }

        if (empty($username) || empty($password)) {
            if (! empty($this->request)) {
                return $this->respond(['error' => 'Username or Password is empty.'], 401);
            }

            return null;
        }

        $user = $userModel->where('username', $username)->orWhere('email', $username)->first();

        if ($user === null) {
            if (! empty($this->request)) {
                return $this->respond(['error' => 'Invalid credentials.', 'username' => $username], 401);
            }

            return null;
        }

        $pwd_verify = password_verify($password, $user['password']);

        if (! $pwd_verify) {
            if (! empty($this->request)) {
                return $this->respond(['error' => 'Invalid credentials.'], 401);
            }

            return null;
        }

        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $nbf = $iat + 10;
        $exp = $iat + 7200;

        $payload = [
            'iss'      => 'iBoot',
            'aud'      => base_url(),
            'sub'      => 'iBoot API',
            'iat'      => $iat, //Time the JWT issued at
            'nbf'      => $nbf, //not before in seconds
            'exp'      => $exp, // Expiration time of token
            'username' => $user['username'],
            'isAdmin'  => $user['isAdmin'],
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'message' => 'Login Successful',
            'token'   => $token,
        ];

        if (! empty($this->request)) {
            return $this->respond($response, 200);
        }

        return $response;
    }
}
