<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Validation;

use iBoot\Models\UserModel;

class UserRules
{
    public function authenticateUser(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        $user  = $model->where('username', $data['username'])->orWhere('email', $data['username'])->first();

        if (! $user) {
            return false;
        }

        return password_verify($data['password'], $user->password);
    }
}
