<?php

namespace iBoot\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Locale implements FilterInterface
{
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

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
