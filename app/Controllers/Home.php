<?php

namespace iBoot\Controllers;

class Home extends BaseController
{
    public function index()
    {
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
