<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use iBoot\Models\UserModel;
use ReflectionException;

class User extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
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
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @throws ReflectionException
     *
     * @return mixed
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
     * Return the editable properties of a resource object
     *
     * @param mixed|null $id
     *
     * @return mixed
     */
    public function edit($id = null)
    {
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @param mixed|null $id
     *
     * @throws ReflectionException
     *
     * @return mixed
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
     * Delete the designated resource object from the model
     *
     * @param mixed|null $id
     *
     * @return mixed
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
