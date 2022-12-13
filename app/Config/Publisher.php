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

use CodeIgniter\Config\Publisher as BasePublisher;

/**
 * Publisher Configuration
 *
 * Defines basic security restrictions for the Publisher class
 * to prevent abuse by injecting malicious files into a project.
 */
class Publisher extends BasePublisher
{
    /**
     * A list of allowed destinations with a (pseudo-)regex
     * of allowed files for each destination.
     * Attempts to publish to directories not in this list will
     * result in a PublisherException. Files that do no fit the
     * pattern will cause copy/merge to fail.
     *
     * @var array<string,string>
     */
    public $restrictions = [
        ROOTPATH => '*',
        FCPATH   => '#\.(s?css|js|map|html?|xml|json|webmanifest|ttf|eot|woff2?|gif|jpe?g|tiff?|png|webp|bmp|ico|svg)$#i',
    ];
}
