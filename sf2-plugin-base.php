<?php
/*
Plugin Name: Symfony2 Plugin Base
Description: A solid plugin development base formed of Symfony2 components
Version: 1.0
Author: Michael Sauter
*/
/**
 * Copyright 2012 Michael Sauter <mail@michaelsauter.net>
 * Sf2PluginBase is a TripleTime project of SitePoint.com
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

$sf2PluginBaseConfig = null;
$sf2PluginBaseClassLoader = null;

add_action('_admin_menu', function() {
    global $sf2PluginBaseConfig;
    global $sf2PluginBaseClassLoader;
    include_once __DIR__.'/includes/bootstrap.php';
});
