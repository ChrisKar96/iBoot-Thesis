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

class ComputerRules
{
    public function valid_mac($str = null): bool
    {
        return filter_var($str, FILTER_VALIDATE_MAC) !== false;
    }

    public function valid_uuid($str = null): bool
    {
        return preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $str) === 1;
    }
}
