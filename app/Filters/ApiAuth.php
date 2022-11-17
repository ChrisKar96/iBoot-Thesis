<?php

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
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param array|null $arguments
     *
     * @return mixed
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
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param array|null $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
