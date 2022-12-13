<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use iBoot\Models\UserLabsModel;
use iBoot\Models\UserModel;

class ApiAuth implements FilterInterface
{
    /**
     * Authenticate to the API using the JWT token provided.
     * The username is decoded from the JWT token and then the user object is constructed.
     *
     * @return ResponseInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $key    = getenv('JWT_SECRET');
        $header = $request->header('Authorization');
        $token  = null;

        // extract the token from the header
        if (! empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        $response = service('response');

        // check if token is null or empty
        if (empty($token)) {
            $response->setBody('Unauthorized');
            $response->setStatusCode(401);

            return $response;
        }

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            $userModel = new UserModel();
            $user      = $userModel->where('username', $decoded->username)->first();

            if ($decoded->iss !== 'iBoot' || $decoded->aud !== base_url() || $decoded->sub !== 'iBoot API' || empty($user)) {
                $response->setBody('Access denied. Token is not valid.');
                $response->setStatusCode(401);

                return $response;
            }

            if (in_array('adminOnly', ($arguments !== null) ? $arguments : [], true) && ! $user['isAdmin']) {
                log_message('notice', 'User {username} tried to perform unauthorized API call {cur_url}', ['username' => $user['username'], 'cur_url' => current_url()]);

                throw new Exception('Access Denied', 401);
            }

            if (! $user['isAdmin']) {
                $userLabsModel = new UserLabsModel();
                $userLabs      = $userLabsModel->select('lab_id')->where('user_id', $user['id'])->findAll();
                $userLabAccess = array_column($userLabs, 'lab_id');
                session()->setFlashdata('userLabAccess', $userLabAccess);
                session()->setFlashdata('userID', $user['id']);
            }
            session()->setFlashdata('userIsAdmin', $user['isAdmin']);
        } catch (ExpiredException $ex) {
            $response->setBody('Access denied. Token is expired.');
            $response->setStatusCode(401);

            return $response;
        } catch (Exception $ex) {
            $response->setBody(! empty($ex->getMessage()) ? $ex->getMessage() : 'Unauthorized');
            $response->setStatusCode(! empty($ex->getCode()) ? $ex->getCode() : 401);

            return $response;
        }
    }

    /**
     * Empty, just for interface satisfaction.
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
