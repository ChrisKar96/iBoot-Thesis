<?php

namespace iBoot\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
use iBoot\Controllers\BaseController;
use iBoot\Models\UserModel;

class User extends BaseController
{
    use ResponseTrait;

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
        $exp = $iat + 3600;

        $payload = [
            'iss'   => 'iBoot',
            'aud'   => $user['username'],
            'sub'   => 'iBoot API',
            'iat'   => $iat, //Time the JWT issued at
            'nbf'   => $nbf, //not before in seconds
            'exp'   => $exp, // Expiration time of token
            'email' => $user['email'],
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
