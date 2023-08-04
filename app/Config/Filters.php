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
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use iBoot\Filters\ApiAuth;
use iBoot\Filters\Auth;
use iBoot\Filters\Locale;
use iBoot\Filters\NoAuth;
use iBoot\Filters\RefreshUserToken;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     */
    public $aliases = [
        'csrf'             => CSRF::class,
        'toolbar'          => DebugToolbar::class,
        'honeypot'         => Honeypot::class,
        'invalidchars'     => InvalidChars::class,
        'secureheaders'    => SecureHeaders::class,
        'auth'             => Auth::class,
        'api-auth'         => ApiAuth::class,
        'no-auth'          => NoAuth::class,
        'locale'           => Locale::class,
        'refreshUserToken' => RefreshUserToken::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     */
    public array $globals = [
        'before' => [
            'honeypot',
            'invalidchars',
            'csrf' => ['except' => ['api/*']],
            'locale',
            'refreshUserToken' => ['except' => ['api/*']],
        ],
        'after' => [
            'toolbar' => ['except' => ['api/*']],
            'honeypot',
            'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public array $filters = [];
}
