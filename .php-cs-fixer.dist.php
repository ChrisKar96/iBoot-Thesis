<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use CodeIgniter\CodingStandard\CodeIgniter4;
use Nexus\CsConfig\Factory;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->files()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/tests',
        __DIR__ . '/public',
    ])
    ->exclude(['Views/errors']);

$options = [
    'finder' => $finder,
];

return Factory::create(new CodeIgniter4(), [], $options)->forLibrary(
    'iBoot',
    'Christos Karamolegkos',
    'iboot@ckaramolegkos.gr',
    2021
);
