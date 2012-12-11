=== Orchestra ===
Contributors: michaelsauter
Tags: symfony2, mvc, options, form
Requires at least: 3.4
Tested up to: 3.4.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A solid plugin development base formed of Symfony2 components.


== Description ==

Orchestra integrates Doctrine2 and some Symfony2 components into Wordpress to allow developers to easily build
admin pages that exceed the built-in Wordpress functionality.

The basic architecture is as follows:
* Orchestra mainly has 3 building blocks (components): Doctrine (ORM), Form, Twig. These can form a foundation for the development of arbitary plugins.
* The components used to form these building blocks are included via Composer
* Orchestra wires the building blocks together and provides additional functionality like class loading as well as a basic router and a controller modelled after the Symfony2 controller.
* Orchestra is initialized during the "_admin_menu" hook
* Then, during the "admin_menu" hook, every plugin based on Orchestra calls the Framework class of Orchestra, which then decides if the calling plugin is active (e.g. the user requested this plugin in the backend). If so, it passes control back to the plugin by executing the correct controller and stores the rendered output. The output must be rendered and stored at this stage, because later on, the headers are already sent.
* The generated output is then retrieved inside the callback of e.g. "add_meu_page".


== Installation ==

1. Put `orchestra` into the `/wp-content/plugins/` directory
2. Inside `orchestra`, run `curl -s https://getcomposer.org/installer | php` and then `php composer.phar install`
3. Edit `config.php` to match your setup
4. Activate the plugin through the 'Plugins' menu in WordPress


== Usage ==

1. Go to `wp-content/plugins`
2. Execute `orchestra/console plugin:create your-plugin-name-here`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. If you use any entities, the databse can be updated via `../orchestra/vendor/bin/doctrine orm:schema-tool:update --force` from your plugin root directory
5. When you want to work with migrations (which is recommended), create a migrations-configuration.yml in you plugin root directory, then run the migrations with the --configuration==migrations-configuration.yml option.
6. While you're developing, make sure to set `WP_DEBUG` in `wp-config.php` to `true` in order to have the caches rewritten automatically

== Frequently Asked Questions ==

= Can I use other Composer libraries, too? =

Yes, it is very easy to do so inside your plugin. You just need to  install Composer, create a composer.json file with your dependencies, install them and pass the namespaces to register as a 3rd parameter to the Framework::setupPlugin() call.

= How do I use the Doctrine CLI? =

From the root of your plugin, you can access the doctrine CLI via `../orchestra/vendor/bin/doctrine`. Always back up your database before you enter any command. Make sure never to call `orm:schema-tool:create` if you are using the same table as Wordpress, otherwise all Wordpress tables will be erased. If you want to create the tables for your entities, use `orm:schema-tool:update`.

= What Coding Style Guidelines to use? =

I recommend following the [Symfony2 Coding Standards](http://symfony.com/doc/2.0/contributing/code/standards.html) instead of the Wordpress Coding Guidelines. Orchestra itself and the templates use the Symfony2 Coding Standards.


== Changelog ==

= 0.9 =
Initial release


== Upgrade Notice ==

= 0.9 =
Initial release