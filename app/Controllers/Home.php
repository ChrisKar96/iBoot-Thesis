<?php

namespace iBoot\Controllers;

use iBoot\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
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
