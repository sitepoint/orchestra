=== Sf2 Plugin Base ===
Contributors: markjaquith, mdawaffe (this should be a list of wordpress.org userid's)
Tags: symfony2, mvc, options, form
Requires at least: 3.4
Tested up to: 3.4.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A solid plugin development base formed of Symfony2 components.


== Description ==

Sf2PluginBase integrates Doctrine2 and some Symfony2 components into Wordpress to allow developers to easily build
admin pages that exceed the built-in Wordpress functionality.

The basic architecture is as follows:
* Sf2PluginBase mainly has 3 building blocks (components): Doctrine (ORM), Form, Twig. These can form a foundation
for the development of arbitary plugins.
* Sf2PluginBase wires these 3 building blocks together and provides additional functionality like class loading
as well as a basic router and a controller.
* During the "admin_menu" hook, every plugin based on Sf2PluginBase calls the Framework class of Sf2PluginBase, which
then decides if the calling plugin is active (e.g. the user requested this plugin in the backend). If so, it passes
control back to the plugin by executing the correct controller and stores the rendered output. The output must be
rendered and stored at this stage, because later on, the headers are already sent.
* The generated output is then retrieved inside the callback of e.g. "add_meu_page".


== Installation ==

1. Put `sf2-plugin-base` into the `/wp-content/plugins/` directory
2. Inside `sf2-plugin-base`, run `curl -s https://getcomposer.org/installer | php` and then `php composer.phar install`
3. Edit `config.php` to match your setup
4. Activate the plugin through the 'Plugins' menu in WordPress


== Usage ==

1. Create a new folder with your plugin name and place it into `wp-content/plugins`
2. Copy the files from `sf2-plugin-base/templates` into the plugin folder
3. Edit the files as required
4. Create cache/, views/ and src/MyCompany/MyPlugin folders
5. Ensure cache/ is writeable by the webserver user


== Frequently Asked Questions ==

= Can I use other composer libraries, too? =

Yes, it is very easy to do so inside your plugin. You just need to  install composer, create a composer.json file with
your dependencies, install them and register the namespaces in the Framework::setupPlugin() call.

= How do I use the Doctrine CLI? =

From the root of your plugin, you can access the doctrine CLI via `../sf2-plugin-base/vendor/bin/doctrine`. Always back
up your database before you enter any command. Make sure never to call `orm:schema-tool:create` if you are using the
same table as Wordpress, otherwise all Wordpress tables will be erased. If you want to create the tables for your
entities, use `orm:schema-tool:update`.


== Changelog ==

= 1.0 =
Initial release


== Upgrade Notice ==

= 1.0 =
Initial release