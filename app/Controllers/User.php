<?php

namespace iBoot\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;
use Exception;
use iBoot\Models\UserModel;
use ReflectionException;

class User extends BaseController
{
    /**
     * @throws Exception
     * @throws ReflectionException
     */
    public function login()
    {
        $data = ['title' => lang('Text.log_in')];

        session()->keepFlashdata('referred_from');

        if ($this->request->getPost('username')
            && $this->request->getPost('password')) {
            helper('form');

            $rules = [
                'username' => 'required|min_length[3]|max_length[320]',
                'password' => 'required|min_length[5]|max_length[255]|authenticateUser[username,password]',
            ];

            $errors = [
                'password' => [
                    'authenticateUser' => lang('Validation.username_or_password_dont_match'),
                ],
            ];

            if (! $this->validate($rules, $errors)) {
                return view('login', [
                    'validation' => $this->validator,
                    'title'      => lang('Text.log_in'),
                ]);
            }

            $model = new UserModel();

            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');

            // Support authenticating with email as well
            $user = $model->where('username', $username)->orWhere('email', $username)->first();

            $login_time = [
                'id'        => $user['id'],
                'lastLogin' => Time::now(),
            ];

            $model->save($login_time);

            // Get user's API token
            $apiUserModel  = new Api\User();
            $user['token'] = $apiUserModel->login($username, $password)['token'];

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
            'id'            => $user['id'],
            'name'          => $user['name'],
            'email'         => $user['email'],
            'phone'         => $user['phone'],
            'username'      => $user['username'],
            'apiToken'      => $user['token'],
            'isAdmin'       => $user['admin'],
            'verifiedEmail' => $user['verifiedEmail'],
            'isLoggedIn'    => true,
        ];

        session()->set($data);
    }

    public function registerAdmin()
    {
        $UserModel         = new UserModel();
        $globalAdminExists = $UserModel->where('admin', 1)->first();
        if (! $globalAdminExists) {
            return $this->signup(true);
        }

        return redirect()->to(base_url('login'));
    }

    /**
     * @param mixed $globalAdmin
     *
     * @throws ReflectionException
     */
    public function signup($globalAdmin = false)
    {
        $title  = $globalAdmin ? lang('Text.sign_up_admin') : lang('Text.sign_up');
        $action = $globalAdmin ? base_url('registerAdmin') : base_url('signup');

        if ($this->request->getMethod() === 'post') {
            helper('form');

            $rules = [
                'name'             => 'required|min_length[3]|max_length[40]',
                'phone'            => 'max_length[15]',
                'email'            => 'required|valid_email|is_unique[users.email,id,{id}]',
                'username'         => 'required|alpha_numeric_punct|min_length[3]|max_length[40]|is_unique[users.username,id,{id}]',
                'password'         => 'required|alpha_numeric_punct|min_length[5]|max_length[255]',
                'password_confirm' => 'required|alpha_numeric_punct|matches[password]',
            ];

            if (! $this->validate($rules)) {
                return view('signup', [
                    'validation' => $this->validator,
                    'title'      => $title,
                    'action'     => $action,
                ]);
            }
            $model = new UserModel();

            $newData = [
                'name'     => $this->request->getVar('name'),
                'phone'    => $this->request->getVar('phone'),
                'email'    => $this->request->getVar('email'),
                'username' => $this->request->getVar('username'),
                'password' => $this->request->getVar('password'),
                'admin'    => $globalAdmin,
                'accepted' => $globalAdmin,
            ];

            $model->save($newData);
            $session = session();
            $session->setFlashdata('success', 'Successful Registration');

            $model->send_validation_email($newData['email']);

            return redirect()->to(base_url('login'));
        }

        return view('signup', [
            'title'  => $title,
            'action' => $action,
        ]);
    }

    public function profile(): string
    {
        $data  = ['title' => lang('Text.profile')];
        $model = new UserModel();

        $data['user'] = $model->where('id', session()->get('id'))->first();

        return view('profile', $data);
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to('login');
    }

    /**
     * @param mixed $email_address
     * @param mixed $email_code
     *
     * @throws ReflectionException
     */
    public function verifyEmail($email_address, $email_code): RedirectResponse
    {
        $model = new UserModel();

        $model->where('email', $email_address)->where('md5(CONCAT(email, created_at))', $email_code)->set(['verifiedEmail' => 1])->update();

		return redirect()->to('login');
    }

    public function send_validation_email($email_address): bool
    {
        $model = new UserModel();

        return $model->send_validation_email($email_address);
    }
}
