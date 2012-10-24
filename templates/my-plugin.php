<?php
/*
Plugin Name: My Plugin
Description: Description
Version: 0.1
Author: My Name
*/

namespace MyCompany\MyPlugin;

class MyPlugin
{
    public function __construct()
    {
        $identifier = \SitePoint\Sf2PluginBase\Framework::setupPlugin(__NAMESPACE__, __DIR__);
        add_menu_page('My Plugin Options', 'My Plugin', 'manage_options', $identifier, array($this, 'output'));
    }

    public function output()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        echo \SitePoint\Sf2PluginBase\Framework::getResponse();
    }
}

add_action('admin_menu', function() {
    new MyPlugin();
});