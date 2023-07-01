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

class Locale implements FilterInterface
{
    /**
     * Set the user's chosen locale.
     *
     * @param mixed|null $arguments
     *
     * @return void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->has('locale')) {
            // Set site language to session locale value
            service('language')->setLocale(session('locale'));
        } else {
            // Save locale to session
            session()->set('locale', service('language')->getLocale());
        }
    }

    /**
     * Empty, just for interface satisfaction.
     *
     * @param mixed|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
