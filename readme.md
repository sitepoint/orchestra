General information
===================
* Contributors: [michaelsauter](https://github.com/michaelsauter)
* Tags: symfony2, mvc, options, form
* Requires at least: 3.5
* Stable tag: 1.1
* License: MIT

Orchestra is a foundation (mainly formed of Symfony2 components) to build WordPress plugins. Its main focus is to aid the development of the admin part of the plugin. It was originally created during a TripleTime project at [SitePoint](http://www.sitepoint.com)


Description
===========

Orchestra integrates Doctrine2 and some Symfony2 components (such as Form and Twig) into WordPress. Orchestra wires those components together and provides a few additional building blocks so that admin plugin development is possible in a similar style to Symfony2 framework development. The public part of the plugin (if any) should be developed using standard WordPress functionality.

The dependencies of Orchestra are specified in the `composer.json` file and are installed into a vendor directory, which is `wp-content/vendors/orchestra` by default. The bootstrap process loads the configuration, `config.php`, which mainly uses the configuration from `wp-config.php`. It also initializes a class loader to take care of loading the dependencies and Orchestra's classes.
This code is executed during the plugin initialization of WordPress, but it does not do anything by itself, but rather is used by plugins based of Orchestra.

Usage
=====

A new Orchestra-based plugin can be created by running `./console plugin:create foo` from `wp-content/plugins/orchestra`. This will create the needed files in `wp-content/plugins/foo`. The Twig templates for the generated files are located in `wp-content/plugins/orchestra/templates`.

As can be seen in the `plugin.php.twig` template, an Orchestra-based plugin inherits from `Orchestra\Plugin`, and can be initialized during the `admin_menu` hook. The constructor takes the namespace, directory, name and version of the plugin. The version information can be used e.g. to update the database automatically when required. If the plugin needs to use AJAX, `Orchestra\Plugin::ajaxCallback` can be used in the `wp_ajax_foo` hook.

The general control flow of an Orchestra-based plugin looks like this:

The constructor of `Orchestra\Plugin` uses `Orchestra\Framework` to setup the plugin, which means it provides the wiring between the different components, and instantiates a `Orchestra\FrontController`, which calls the apropriate plugin controller. The generated response is retrieved via `Orchestra\Plugin#output`. The wiring mainly consists of the following steps:

* Setup a `Symfony\Component\HttpFoundation\Request` from available globals
* Register namespaces and prefixes of the plugin with the class loader
* Bootstrap Doctrine. This uses the configuration located in the plugin, which specifies directories for entities and generated proxies. It also injects a global table prefix determined by `$wpdb->prefix`.
* Setup Twig. Templates are cached in `prod` environment, but not in `dev`. To help with plugin development, the functions `url`, `path`, `ajax_url` and `ajax_path` are made available, which generate a URL or path based on controller / action arguments. Standard WordPress functions are available as well via the `wp` object, which just "proxies" to a WordPress function such as `bloginfo()`.


Installation
============

1. Put `orchestra` into the `/wp-content/plugins/` directory
2. Run `composer install` (requires composer to be installed globally)
3. (Optionally) edit `config.php` to match your setup
4. Activate the plugin through the 'Plugins' menu in WordPress
5. While you're developing, make sure to set `WP_DEBUG` in `wp-config.php` to `true` in order to have the caches rewritten automatically


Interacting With the Database
=============================
Orchestra interacts with the database via Doctrine2. It also supports multisite setup out-of-the-box. When creating entities, you should specify the table name explicitly, but leave out the prefix as this is determined by Orchestra.
To create or modify the database schema, you need to provide install / update routines in your plugin and run SQL from there. Unfortunately, the Doctrine CLI tools are not supported. That being said, it is often more comfortable to handle schema changes upon install/update anyway. Please note that due to the nature of WordPress' database setup, Doctrine cannot reuse the existing databse connection and must establish a second one. Therefore, it is not advisable to run an Orchestra-based plugin during a non-admin request.


Writing Tests
=============
You can write unit tests just like for every other PHP project. Orchestra uses PHPUnit to test its code (well, a tiny portion of it). If you want to test your plugin with PHPUnit, take a look inside `tests/` to get started.


Deployment
==========
The repository intentionally does not contain the vendors. The vendor directory is configured by default to be `wp-content/vendors/orchestra`. Exclude this folder from version control if you want to install the dependencies via composer upon install. If you want to manage the dependencies locally however, commit the folder to your repository.


Frequently Asked Questions
==========================

Can I use other Composer libraries, too?
----------------------------------------

Yes, it is very easy to do so inside your plugin. You just need to  install Composer, create a composer.json file with your dependencies, install them and pass the namespaces to register as a 3rd parameter to the Framework::setupPlugin() call.

What Coding Style Guidelines to use?
------------------------------------

I recommend following the [Symfony2 Coding Standards](http://symfony.com/doc/2.0/contributing/code/standards.html) instead of the Wordpress Coding Guidelines. Orchestra itself and the templates use the Symfony2 Coding Standards.


Changelog
=========

1.2
---
* Improve path locations
* Cache metadata on non-multisite setups
* Allow required capability to be customzized
* Update plugin template to load Orchestra if not loaded yet

1.1
---
* Removed Doctrine Migrations
* Fixed unit tests
* Updated readme

1.0
---
* Initial release