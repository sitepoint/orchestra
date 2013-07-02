<?php
/**
 * Copyright (c) 2012-2013 Michael Sauter <mail@michaelsauter.net>
 * Orchestra originated from a TripleTime project of SitePoint.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

$orchestraConfig = array(
    'database' => array(
        'driver' => 'pdo_mysql', // only MySQL is supported right now
        'host' => (defined('DB_HOST') ? DB_HOST : 'localhost'),
        'user' => (defined('DB_USER') ? DB_USER : 'root'),
        'password' => (defined('DB_PASSWORD') ? DB_PASSWORD : ''),
        'dbname' => (defined('DB_NAME') ? DB_NAME : 'wordpress')
    ),
    'csrfSecret' => (defined('AUTH_SALT') ? AUTH_SALT : 'auth_salt'),
    'env' => ((defined('WP_DEBUG') ? WP_DEBUG : false) ? 'dev' : 'prod'),
    'language' => 'en', // only "en" is supported right now
    'multisite' => (defined('WP_ALLOW_MULTISITE') ? WP_ALLOW_MULTISITE : false),
    'vendorDir' => ABSPATH.'wp-content/vendors/orchestra'
);