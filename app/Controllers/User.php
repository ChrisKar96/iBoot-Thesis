<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function login()
    {
        $data = ['title' => 'Log In'];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[255]',
                'password' => 'required|min_length[5]|max_length[255]|validateUser[username,password]',
            ];

            $errors = [
                'password' => [
                    'validateUser' => "Username or Password don't match",
                ],
            ];

            if (! $this->validate($rules, $errors)) {
                return view('login', [
                    'validation' => $this->validator,
                    'title'      => 'Log In',
                ]);
            }
            $model = new UserModel();

            $user = $model->where('username', $this->request->getVar('username'))->first();

            // Storing session values
            $this->setUserSession($user);
            // Redirecting to dashboard after login
            return redirect()->to(base_url('dashboard'));
        }

        return view('login', $data);
    }

    private function setUserSession($user)
    {
        $data = [
            'id'         => $user['id'],
            'name'       => $user['name'],
            'phone'      => $user['phone'],
            'username'   => $user['username'],
            'isLoggedIn' => true,
        ];

        session()->set($data);
    }

    public function register()
    {
        $data = ['title' => 'Register'];

        if ($this->request->getMethod() === 'post') {
            //let's do the validation here
            $rules = [
                'name'             => 'required|min_length[3]|max_length[255]',
                'phone'            => 'max_length[15]',
                'username'         => 'required|min_length[3]|max_length[255]',
                'password'         => 'required|min_length[5]|max_length[255]',
                'password_confirm' => 'matches[password]',
            ];

            if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;

                return view('register', [
                    'validation' => $this->validator,
                ]);
            }
            $model = new UserModel();

            $newData = [
                'name'     => $this->request->getVar('name'),
                'phone'    => $this->request->getVar('phone'),
                'username' => $this->request->getVar('username'),
                'password' => $this->request->getVar('password'),
            ];
            $model->save($newData);
            $session = session();
            $session->setFlashdata('success', 'Successful Registration');

            return redirect()->to(base_url('login'));
        }

        return view('register', $data);
    }

    public function profile()
    {
        $data  = ['title' => 'Profile'];
        $model = new UserModel();

        $data['user'] = $model->where('id', session()->get('id'))->first();

        return view('profile', $data);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('login');
    }
}
