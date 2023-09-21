<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;
use Config\Services;
use Exception;
use iBoot\Models\ForgotPasswordTokenModel;
use iBoot\Models\UserModel;
use ReflectionException;

class User extends BaseController
{
    public function index(): string
    {
        return view(
            'table',
            [
                'title'     => lang('Text.users'),
                'tabulator' => true,
                'apiTarget' => base_url('/api/user'),
                'columns'   => '{title:"' . lang('Text.fullname') . '", field:"name", sorter:"string", editor:"input", headerFilter:"input", validator:["required","string","maxLength:40"], frozen:true},
                                {title:"' . lang('Text.email') . '", field:"email", sorter:"string", editor:"input", headerFilter:"input", validator:["required","unique","string","maxLength:320"]},
                                {title:"' . lang('Text.phone') . '", field:"phone", sorter:"string", editor:"input", headerFilter:"input", validator:["maxLength:15"]},
                                {title:"' . lang('Text.username') . '", field:"username", sorter:"string", editor:"input", headerFilter:"input", validator:["required","unique","string","minLength:3","maxLength:40"]},
                                {title:"' . lang('Text.password') . '", field:"password", editor:"input", validator:["required","minLength:5","maxLength:255"]},
                                {title:"' . lang('Text.administrator') . '", field:"isAdmin", sorter:"string", editor:"tickCross", formatter:"tickCross", headerFilter:"tickCross",  headerFilterParams:{"tristate":true},headerFilterEmptyCheck:function(value){return value === null}},
                                {title:"' . lang('Text.verifiedEmail') . '", field:"verifiedEmail", sorter:"string", editor:"tickCross", formatter:"tickCross", headerFilter:"tickCross",  headerFilterParams:{"tristate":true},headerFilterEmptyCheck:function(value){return value === null}},
                                {title:"' . lang('Text.labs') . '", field:"labs", sorter:"string", editor:"list", headerSort:true,
                                    headerFilter:"list",
                                    headerFilterFunc:multiListHeaderFilter,
                                    headerFilterEmptyCheck:function(value){
                                        return !value && !value.length;
                                    },
                                    headerFilterParams: {
                                        values:labs,
                                        clearable:true,
                                        multiselect:true
                                    },
                                    editorParams:{
                                        multiselect:true,
                                        clearable:true,
                                        values:labs
                                    },
                                    formatter:function (cell, formatterParams, onRendered) {
                                        if(typeof cell.getValue() !== "undefined" && cell.getValue().length !== 0){
                                            values = cell.getValue().toString().split(",");
                                            let formatted = "";
                                            for(i = 0; i < values.length; ++i) {
                                                if(typeof formatterParams[values[i]] === "undefined") {
                                                    console.warn(\'Missing display value for \' + values[i]);
                                                    return values[i];
                                                }
                                                formatted += formatterParams[values[i]];
                                                if(i < values.length - 1)
                                                    formatted += ", ";
                                            }
                                            return formatted;
                                        }
                                    },
                                    formatterParams: labs,
                                },
                                {title:"' . lang('Text.created_at') . '", field:"created_at", sorter:"datetime", sorterParams:{format:"yyyy-MM-dd HH:mm:ss"}, formatter:"datetime"},
                                {title:"' . lang('Text.updated_at') . '", field:"updated_at", sorter:"datetime", sorterParams:{format:"yyyy-MM-dd HH:mm:ss"}, formatter:"datetime"},',
                'JS_bef_tb' => 'let labs = {};

                                async function getLabs(){
                                    await api_call("' . base_url('/api/lab') . '", "GET").then(function(response) {
                                        labs[null] = "-";
                                        for (i = 0; i < response.length; i++) {
                                            labs[response[i].id] = response[i].name;
                                        }
                                    });
                                }

                                getLabs();
                ',
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function login()
    {
        $data = ['title' => lang('Text.log_in')];

        $model      = new UserModel();
        $usersExist = $model->first();
        if (! $usersExist) {
            return redirect()->to(base_url());
        }

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

            $username = $this->request->getPost('username');

            // Support authenticating with email as well
            $user = $model->where('username', $username)->orWhere('email', $username)->first();

            // Get user's API token
            $user->generateAPItoken();

            // Storing session values
            $this->setUserSession($user);
            // Redirecting to dashboard after login
            $referred_from = (string) session()->get('referred_from');
            if ($referred_from) {
                session()->remove($referred_from);

                return redirect()->to($referred_from);
            }
            log_message('info', 'User {username} logged into the system using the web interface from {ip}', ['username' => $username, 'ip' => $this->request->getIPAddress()]);

            return redirect()->to(base_url('dashboard'));
        }

        return view('login', $data);
    }

    private function setUserSession($user)
    {
        $data = [
            'user'       => $user,
            'isLoggedIn' => true,
        ];

        session()->set($data);
    }

    public function registerAdmin()
    {
        $UserModel         = new UserModel();
        $globalAdminExists = $UserModel->where('isAdmin', 1)->first();
        if (! $globalAdminExists) {
            return $this->signup(true);
        }

        return redirect()->to(base_url('login'));
    }

    /**
     * @param mixed $globalAdmin
     */
    public function signup($globalAdmin = false)
    {
        $data['title']  = $globalAdmin ? lang('Text.sign_up_admin') : lang('Text.sign_up');
        $data['action'] = $globalAdmin ? base_url('registerAdmin') : base_url('signup');

        if ($this->request->getPost('name')
            && $this->request->getPost('email')
            && $this->request->getPost('username')
            && $this->request->getPost('password')
            && $this->request->getPost('password_confirm')) {
            helper('form');

            $rules = [
                'name'             => 'required|min_length[3]|max_length[40]',
                'phone'            => 'permit_empty|min_length[3]|max_length[15]',
                'email'            => 'required|valid_email|is_unique[users.email,id,{id}]',
                'username'         => 'required|alpha_numeric_punct|min_length[3]|max_length[40]|is_unique[users.username,id,{id}]',
                'password'         => 'required|alpha_numeric_punct|min_length[5]|max_length[255]',
                'password_confirm' => 'required|alpha_numeric_punct|matches[password]',
            ];

            if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;

                return view('signup', $data);
            }
            $model = new UserModel();

            $newData = [
                'name'     => $this->request->getVar('name'),
                'email'    => $this->request->getVar('email'),
                'phone'    => $this->request->getVar('phone'),
                'username' => $this->request->getVar('username'),
                'password' => $this->request->getVar('password'),
                'isAdmin'  => $globalAdmin ? 1 : 0,
            ];

            try {
                if ($model->save($newData)) {
                    $newData['password'] = '********';
                    log_message('debug', "new user registration: {username}\n{data}", ['username' => $newData['username'], 'data' => var_export($newData, true)]);
                } else {
                    log_message('debug', "new user {username} registration errors:\n{data}", ['username' => $newData['username'], 'data' => var_export($model->errors(), true)]);
                }
                $session = session();
                $session->setFlashdata('success', 'Successful Registration');

                $this->sendValidationEmail($newData['email']);

                return redirect()->to(base_url());
            } catch (ReflectionException $e) {
                $data['error'] = $e;

                return view('signup', $data);
            }
        }

        return view('signup', $data);
    }

    public function profile($data = null): string
    {
        $data = ['title' => lang('Text.profile')];

        if ($this->request->getPost('id') && $this->request->getPost('password') && $this->request->getPost('old_password') && $this->request->getPost('confirm_password')) {
            $post = [
                'id'               => $this->request->getPost('id'),
                'old_password'     => $this->request->getPost('old_password'),
                'password'         => $this->request->getPost('password'),
                'confirm_password' => $this->request->getPost('confirm_password'),
            ];

            helper('form');

            $rules = [
                'id'               => 'is_natural_no_zero|max_length[10]|permit_empty',
                'old_password'     => 'alpha_numeric_punct|min_length[5]|max_length[255]|required',
                'password'         => 'alpha_numeric_punct|min_length[5]|max_length[255]|required',
                'confirm_password' => 'alpha_numeric_punct|min_length[5]|max_length[255]|required|matches[password]',
            ];

            if (! $this->validate($rules)) {
                return $this->profile([
                    'validationChangePassword' => $this->validator->listErrors(),
                ]);
            }
            $model = new UserModel();/*
            if (! $model->validate($post)) {
                return $this->profile([
                    'validationChangePassword' => $model->errors(),
                ]);
            }*/

            $user = $model->where('id', $post['id'])->first();

            if ($user && $user->password_verify($post['old_password'], $user->password)) {
                unset($post['old_password'], $post['confirm_password']);
                if ($model->save($post)) {
                    $data['msgChangePassword'] = true;
                }
            }
        } elseif ($this->request->getPost('id') && $this->request->getPost('email')) {
            $post = [
                'id'    => $this->request->getPost('id'),
                'email' => $this->request->getPost('email'),
            ];

            helper('form');

            $rules = [
                'id'    => 'is_natural_no_zero|max_length[10]|permit_empty',
                'email' => 'valid_email|max_length[320]|required',
            ];

            if (! $this->validate($rules)) {
                return $this->profile([
                    'validationChangeEmail' => $this->validator->listErrors(),
                ]);
            }
            $model = new UserModel();/*
            if (! $model->validate($post)) {
                return $this->profile([
                    'validationChangeEmail' => $model->errors(),
                ]);
            }*/
            $post['verifiedEmail'] = 0;

            $user = $model->where('id', $post['id'])->first();

            if ($user) {
                if ($model->save($post)) {
                    $this->sendValidationEmail($post['email']);
                    $data['msgChangeEmail'] = TRUE;
                }
            }
        }

        return view('profile', $data);
    }

    public function logout(): RedirectResponse
    {
        $user = session()->get('user');
        session()->destroy();
        log_message('info', 'User {username} logged out', ['username' => $user->username]);

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

        $model->where('email', $email_address)->where('md5(CONCAT(email, created_at, updated_at))', $email_code)->set(['verifiedEmail' => 1])->update();
        $userSession = session()->get('user');
        if (! empty($userSession)) {
            $userSession['verifiedEmail'] = true;
            session()->set('user', $userSession);
        }

        return redirect()->to('login');
    }

    public function sendValidationEmail($email_address): bool
    {
        $model = new UserModel();

        $user = $model->where('email', $email_address)->first();

        if (! empty($user)) {
            $email_code = md5($email_address . $user->created_at . $user->updated_at);

            $email = Services::email();

            $email->setTo($email_address);

            $email->setMailType('html');
            $email->setSubject('iBoot email verification');

            $message = '<p>Hi ' . $user->name . ',</p>';

            $message .= '<p>Please confirm your email address for your account with username <strong>' . $user->username . '</strong>.</p>';
            $message .= '<p>Click the link below to confirm your email address ' . $email_address . '</p>';
            $message .= '<p><a href="' . base_url('verifyEmail/' . $email_address . '/' . $email_code) . '">Confirm your email address</a></p>';

            $email->setMessage($message);

            return $email->send();
        }

        return false;
    }

    /**
     * @param mixed|null $data
     *
     * @throws Exception
     */
    public function forgotCredentials($data = null): string
    {
        $data['title'] = lang('Text.forgot_credentials');

        return view('forgotCredentials', $data);
    }

    /**
     * @throws Exception
     */
    public function forgotUsername(): string
    {
        if ($this->request->getPost('email')) {
            $email_address = $this->request->getPost('email');

            helper('form');

            $rules = [
                'email' => 'required|valid_email',
            ];

            if (! $this->validate($rules)) {
                return $this->forgotCredentials([
                    'validationForgotUsername' => $this->validator,
                ]);
            }
            $model = new UserModel();
            $user  = $model->where('email', $email_address)->first();

            if (empty($user)) {
                return $this->forgotCredentials([
                    'userNotFoundUsername' => true,
                ]);
            }

            $email = Services::email();

            $email->setTo($user->email);

            $email->setMailType('html');
            $email->setSubject('iBoot username reminder');

            $message = '<p>Hi ' . $user->name . ',</p>';

            $message .= '<p>Your username is <strong>' . $user->username . '</strong>.</p>';

            $email->setMessage($message);

            $reminderSent = $email->send();

            return $this->forgotCredentials([
                'reminderSentUsername' => $reminderSent,
            ]);
        }

        return $this->forgotCredentials();
    }

    /**
     * @throws Exception
     * @throws ReflectionException
     */
    public function forgotPassword(): string
    {
        if ($this->request->getPost('username')) {
            $username = $this->request->getPost('username');

            helper('form');

            $rules = [
                'username' => 'required|min_length[3]|max_length[320]',
            ];

            if (! $this->validate($rules)) {
                return $this->forgotCredentials([
                    'validationForgotPassword' => $this->validator,
                ]);
            }
            $userModel = new UserModel();
            $user      = $userModel->where('username', $username)->orWhere('email', $username)->first();

            if (empty($user)) {
                return $this->forgotCredentials([
                    'validationForgotPassword' => $this->validator,
                    'userNotFoundPassword'     => true,
                ]);
            }

            $token      = urlencode(md5($user->id . Time::now()));
            $token_exp  = Time::now()->addMinutes(15);
            $token_data = [
                'user_id'  => $user->id,
                'token'    => $token,
                'exp_date' => $token_exp,
            ];

            $forgotPasswordTokenModel = new forgotPasswordTokenModel();
            $forgotPasswordTokenModel->save($token_data);

            $email = Services::email();

            $email->setTo($user->email);

            $email->setMailType('html');
            $email->setSubject('iBoot reissue password');

            $message = '<p>Hi ' . $user->name . ',</p>';

            $message .= '<p><a href="' . base_url('forgotPassword/token/' . $token) . '">Click here</a> to reissue your password.</p>';
            $message .= '<p>The link will be valid until ' . $token_exp . '</p>';

            $email->setMessage($message);

            $reminderSent = $email->send();

            return $this->forgotCredentials([
                'validationForgotPassword' => $this->validator,
                'reminderSentPassword'     => $reminderSent,
            ]);
        }

        return $this->forgotCredentials();
    }

    /**
     * @throws Exception
     * @throws ReflectionException
     */
    public function forgotPasswordToken(string $token): string
    {
        $data          = ['title' => lang('Text.forgot_password')];
        $data['token'] = $token;

        $forgotPasswordTokenModel = new forgotPasswordTokenModel();
        $user                     = $forgotPasswordTokenModel->where('token', $token)->where('exp_date >', Time::now())->first();

        if (empty($user)) {
            $data['tokenInvalid'] = true;

            return view('reissuePassword', $data);
        }

        if ($this->request->getPost('password')
            && $this->request->getPost('password_confirm')) {
            helper('form');

            $rules = [
                'password'         => 'required|alpha_numeric_punct|min_length[5]|max_length[255]',
                'password_confirm' => 'required|alpha_numeric_punct|matches[password]',
            ];

            if (! $this->validate($rules)) {
                $data['validationReissuePassword'] = $this->validator;

                return view('reissuePassword', $data);
            }
            $userModel = new UserModel();
            $userModel->where('id', $user->user_id);
            $data['passwordChanged'] = $this->changePassword($user->user_id, $this->request->getVar('password'));
            $forgotPasswordTokenModel->delete($user->user_id);
        }

        return view('reissuePassword', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function changePassword(int $id, string $password): bool
    {
        $userModel = new UserModel();

        return $userModel->save([
            'id'       => $id,
            'password' => $password,
        ]);
    }
}
