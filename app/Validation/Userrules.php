<?php

namespace iBoot\Validation;

use iBoot\Models\UserModel;

class Userrules
{
    public function authenticateUser(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        $user  = $model->where('username', $data['username'])->orWhere('email', $data['username'])->first();

        if (! $user) {
            return false;
        }

        return password_verify($data['password'], $user['password']);
    }
}
