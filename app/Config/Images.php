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
use CodeIgniter\Images\Handlers\GDHandler;
use CodeIgniter\Images\Handlers\ImageMagickHandler;

class Images extends BaseConfig
{
    /**
     * Default handler used if no other handler is specified.
     */
    public string $defaultHandler = 'gd';

    /**
     * The path to the image library.
     * Required for ImageMagick, GraphicsMagick, or NetPBM.
     */
    public string $libraryPath = '/usr/local/bin/convert';

    /**
     * The available handler classes.
     *
     * @var array<string, string>
     */
    public array $handlers = [
        'gd'      => GDHandler::class,
        'imagick' => ImageMagickHandler::class,
    ];
}
