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

namespace Orchestra;

/**
 * Provides basic functionality for an Orchestra plugin
 */
class Plugin
{

    static public $namespace;
    static public $directory;
    static public $version;
    static public $key;

    public function __construct($namespace, $directory, $name, $version)
    {
        // Check the data directories exist and are writeable
        if (!file_exists($directory.'/data/cache') || !file_exists($directory.'/data/proxies') || !is_writable($directory.'/data/cache') || !is_writable($directory.'/data/proxies')) {
            wp_die('You need to ensure both "'.$directory.'/data/cache" and "'.$directory.'/data/proxies" exist and are writable by Apache\'s user.');
        }

        // Setup the plugin and add the menu page
        try {
          $identifier = Framework::setupPlugin($namespace, $directory);
        } catch (\Exception $exception) {
          Framework::displayError($exception);
        }
        $pageHookSuffix = add_menu_page($name, $name, 'manage_options', $identifier, array($this, 'output'));

        // Initialize the public variables
        self::$namespace = $namespace;
        self::$directory = $directory;
        self::$key = $identifier;
        self::$version = $version;

        add_action('admin_print_scripts-' . $pageHookSuffix, array($this, 'enqeueAssets'));
    }

    /**
     * Retrieves saved response from the framework
     */
    public function output()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        echo Framework::getResponse();
    }

    /**
     * AJAX callback to be used when the plugin wants to do an AJAX request
     * Just put the following line in your plugin file:
     * add_action('wp_ajax_your_plugin', array('\Your\Plugin', 'ajaxCallback'));
     */
    static public function ajaxCallback($namespace, $directory)
    {
        Framework::setupPlugin($namespace, $directory);
        echo Framework::getResponse();
        die();
    }

    /**
     * Returns path to the public folder under resources/
     */
    static public function getPublicResourcesUrl()
    {
        return plugin_dir_url(self::$directory.'/some-file').'resources/public';
    }

    /**
     * Installs this plugin
     * Adds a version option to the database
     */
    static public function install()
    {
        add_option(self::getDatabaseVersionOptionName(), self::$version);
    }

    /**
     * Updates the version of this plugin
     * Iterates over given versions, which must be an associative array
     * of version numbers and functions doing the actual work
     */
    static public function update($versions)
    {
        $installedVersion = self::getInstalledVersion();
        if ($installedVersion != self::$version) {
            foreach ($versions as $version => $command) {
                if ($installedVersion < $version) {
                    $command();
                }
            }
        }
        update_option(self::getDatabaseVersionOptionName(), self::$version);
    }

    /**
     * Gets the installed version of this plugin
     */
    static public function getInstalledVersion()
    {
        return get_option(self::getDatabaseVersionOptionName());
    }

    /**
     * Returns the name of the version option of this plugin
     */
    static public function getDatabaseVersionOptionName()
    {
        global $wpdb;
        return self::$key.'_version_'.$wpdb->prefix;
    }

    /**
     * Hook to enqeue styles and scripts.
     * The hook is registered in the constructor.
     * See http://codex.wordpress.org/Function_Reference/wp_enqueue_script#Link_Scripts_Only_on_a_Plugin_Administration_Screen
     */
    public function enqeueAssets()
    {

    }
}