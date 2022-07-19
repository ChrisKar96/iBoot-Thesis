<?php

namespace iBoot\Controllers;

use Config\Services;
use iBoot\Models\UserModel;
use Throwable;

class Home extends BaseController
{
    public function index()
    {
        $migrate = Services::migrations();

        try {
            $migrate->latest();
        } catch (Throwable $e) {
            // Do something with the error here...
            echo $e->getMessage();
        }

        $UserModel         = new UserModel();
        $globalAdminExists = $UserModel->where('isAdmin', 1)->first();
        if (! $globalAdminExists) {
            return redirect()->to(base_url('registerAdmin'));
        }

        return redirect()->to(base_url('dashboard'));
    }

    public function boot()
    {
        return view('boot', ['title' => 'iPXE Boot Menu']);
    }

    public function initboot()
    {
        return view('initboot', ['title' => 'iPXE Boot Menu']);
    }
}
