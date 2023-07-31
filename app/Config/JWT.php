<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    // Random string used as JWT secret key
    // Generate using 'openssl rand -base64 30' or similar command
    public string $secret = 'Change_this_to_a_random_string';
}
