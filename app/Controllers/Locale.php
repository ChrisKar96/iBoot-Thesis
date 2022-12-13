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

use CodeIgniter\Controller;

/**
 * Change site locale to requested one
 */
class Locale extends Controller
{
    // Set application wide locale
    public function set(string $locale): \CodeIgniter\HTTP\RedirectResponse
    {
        // Check requested language exist in \Config\App
        if (in_array($locale, config('App')->supportedLocales, true)) {
            // Save requested locale in session, will be set by filter
            session()->set('locale', $locale);

            // Reload page
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }

        throw new \CodeIgniter\Exceptions\PageNotFoundException(esc($locale) . ' is not a supported language');
    }
}
