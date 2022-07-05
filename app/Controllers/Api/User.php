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

        if (! empty($username)) {
            $user = $userModel->where('username', $username)->orWhere('email', $username)->first();
        }

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
        $exp = $iat + 3600;

        $payload = [
            'iss'   => 'Issuer of the JWT',
            'aud'   => 'Audience that the JWT',
            'sub'   => 'Subject of the JWT',
            'iat'   => $iat, //Time the JWT issued at
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
