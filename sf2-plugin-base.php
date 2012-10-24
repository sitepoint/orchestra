<?php
/*
Plugin Name: Symfony2 Plugin Base
Description: A solid plugin development base formed of Symfony2 components
Version: 0.1
Author: Michael Sauter
*/
include_once __DIR__.'/config.php';
include_once __DIR__.'/src/SitePoint/Sf2PluginBase/ClassLoader.php';
\SitePoint\Sf2PluginBase\ClassLoader::init();