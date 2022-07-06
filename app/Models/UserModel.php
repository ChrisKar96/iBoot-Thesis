<?php

namespace iBoot\Models;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Model;
use Config\Services;
use ReflectionException;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password',
        'name',
        'email',
        'phone',
        'admin',
        'accepted',
        'verifiedEmail',
        'lastLogin',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['beforeInsert'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to('login');
    }

    protected function beforeInsert(array $data): array
    {
        return $this->passwordHash($data);
    }

    protected function passwordHash(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    public function send_validation_email($email_address): bool
    {
        $user = $this->where('email', $email_address)->first();

        if (! empty($user)) {
            $email_code = md5($email_address . $user['created_at']);

            $email = Services::email();

            $email->setTo($email_address);

            $email->setMailType('html');
            $email->setSubject('iBoot email verification');

            $message = '<p>Hi ' . $user['name'] . ',</p>';

            $message .= '<p>Please confirm your email address for your account with username <strong>' . $user['username'] . '</strong>.</p>';
            $message .= '<p>Click the link below to confirm your email address ' . $email_address . '</p>';
            $message .= '<p><a href="' . base_url('verifyEmail/' . $email_address . '/' . $email_code) . '">Confirm your email address</a></p>';

            $email->setMessage($message);

            return $email->send();
        }

        return false;
    }

}
