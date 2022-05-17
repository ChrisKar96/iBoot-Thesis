<?php

namespace iBoot\Controllers;

use iBoot\Models\UserModel;

class User extends BaseController
{
    public function login()
    {
        $data = ['title' => lang('Text.log_in')];

        session()->keepFlashdata('referred_from');

        if ($this->request->getMethod() === 'post') {
            helper('form');

            $rules = [
                'username' => 'required|min_length[3]|max_length[255]',
                'password' => 'required|min_length[5]|max_length[255]|validateUser[username,password]',
            ];

            $errors = [
                'password' => [
                    'validateUser' => lang('Validation.username_or_password_dont_match'),
                ],
            ];

            if (! $this->validate($rules, $errors)) {
                return view('login', [
                    'validation' => $this->validator,
                    'title'      => lang('Text.log_in'),
                ]);
            }
            $model = new UserModel();

            $user = $model->where('username', $this->request->getVar('username'))->first();

            // Storing session values
            $this->setUserSession($user);
            // Redirecting to dashboard after login
            if ($referred_from = (string) session()->getFlashdata('referred_from')) {
                return redirect()->to($referred_from);
            }

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

    public function signup()
    {
        $data = ['title' => lang('Text.sign_up')];

        if ($this->request->getMethod() === 'post') {
            helper('form');

            $rules = [
                'name'             => 'required|min_length[3]|max_length[255]',
                'phone'            => 'max_length[15]',
                'username'         => 'required|min_length[3]|max_length[255]',
                'password'         => 'required|min_length[5]|max_length[255]',
                'password_confirm' => 'matches[password]',
            ];

            if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;

                return view('signup', [
                    'validation' => $this->validator,
                    'title'      => lang('Text.sign_up'),
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

        return view('signup', $data);
    }

    public function profile()
    {
        $data  = ['title' => lang('Text.profile')];
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
