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
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    /**
     * Require user is logged in to access a page, otherwise redirect them to the login page.
     * Optionally, require the user to have admin privileges to access the page, otherwise redirect them to their dashboard.
     *
     * @return RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('isLoggedIn')) {
            session()->set('referred_from', current_url());

            return redirect()->to(site_url('login'));
        }

        $user = session()->get('user');

        if (in_array('adminOnly', ($arguments !== null) ? $arguments : [], true) && ! $user['isAdmin']) {
            log_message('notice', 'User {username} tried to illegally access {cur_url}', ['username' => $user['username'], 'cur_url' => current_url()]);

            return redirect()->to(site_url('dashboard'));
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
