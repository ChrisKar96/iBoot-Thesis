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
use iBoot\Controllers\User;

class RefreshUserToken implements FilterInterface
{
    /**
     * Refresh the user's API token if their session is extended.
     *
     * @return void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->has('iBootSessionID')) {
            session()->set('iBootSessionID', session()->get('__ci_last_regenerate'));
        } elseif (session()->get('iBootSessionID') !== session()->get('__ci_last_regenerate')) {
            if (session()->has('user')) {
                $user = new User();
                $user->refreshUserToken();
            }
            session()->set('iBootSessionID', session()->get('__ci_last_regenerate'));
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
