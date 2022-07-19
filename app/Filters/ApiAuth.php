<?php

namespace iBoot\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

        // check if token is null or empty
        if (empty($token)) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);

            return $response;
        }

        try {
            $decoded  = JWT::decode($token, new Key($key, 'HS256'));
            $response = service('response');

            if ($decoded->iss !== 'iBoot' || $decoded->aud !== base_url() || $decoded->sub !== 'iBoot API') {
                $response->setBody('Token not valid. Access denied');
                $response->setStatusCode(401);

                return $response;
            }
        } catch (Exception $ex) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);

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
