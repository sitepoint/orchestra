<?php
/**
 * Copyright 2012 Michael Sauter <michael.sauter@sitepoint.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
namespace SitePoint\Sf2PluginBase\Twig;

/**
 * This class just tries to call the given $function,
 * allowing users to call Wordpress functions in the template
 *
 * Example: {{ wp.bloginfo() }}
 */
class WordpressProxy {
    public function __call($function, $arguments) {
        if (!function_exists($function)) {
            throw new BadFunctionCallException('Call to undefined function ' . $function);
        }
        return call_user_func_array($function, $arguments);
    }
}
