<?php

namespace iBoot\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('dashboard', ['title' => lang('Text.dashboard')]);
    }
}
