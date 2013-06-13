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

namespace Orchestra\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class CreatePluginCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('plugin:create')
            ->setDescription('Create a new plugin')
            ->addArgument(
                'pluginIdentifier',
                InputArgument::REQUIRED,
                'What is the hyphenized name of the plugin (e.g. "my-plugin")?'
            )
        ;
    }

    /**
     * From the specified plugin name, create a directory
     * Create views/, cache/ and src/Namespace directories
     * Generate cli-config.php, doctrine-config.php and plugin-identifier.php
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pluginIdentifier = $input->getArgument('pluginIdentifier');
        $pluginNameParts = explode('-', $pluginIdentifier);
        array_walk($pluginNameParts, function (&$value, $key) {
            $value = ucfirst($value);
        });
        $pluginNamespace = join('', $pluginNameParts);
        $pluginName =  join(' ', $pluginNameParts);

        $fs = new Filesystem();

        // create directories
        try {
            $fs->mkdir(array(
                '../'.$pluginIdentifier,
                '../'.$pluginIdentifier.'/resources',
                '../'.$pluginIdentifier.'/resources/views',
                '../'.$pluginIdentifier.'/resources/public',
                '../'.$pluginIdentifier.'/data',
                '../'.$pluginIdentifier.'/data/cache',
                '../'.$pluginIdentifier.'/data/proxies',
                '../'.$pluginIdentifier.'/src',
                '../'.$pluginIdentifier.'/src/'.$pluginNamespace,
                '../'.$pluginIdentifier.'/src/'.$pluginNamespace.'/Controller',
                '../'.$pluginIdentifier.'/src/'.$pluginNamespace.'/Entity',
                '../'.$pluginIdentifier.'/src/'.$pluginNamespace.'/Form/Type'
            ));
        } catch (IOException $e) {
            $output->writeln('An error occured while creating directories: '.$e->getMessage());
        }

        $templatesDirectory = __DIR__.'/../../../templates';
        // generate files
        $loader = new \Twig_Loader_Filesystem($templatesDirectory);
        $twig = new \Twig_Environment($loader);

        file_put_contents('../'.$pluginIdentifier.'/doctrine-config.php', $twig->render('doctrine-config.php.twig', array(
            'pluginIdentifier' => $pluginIdentifier,
            'pluginNamespace' => $pluginNamespace
        )));

        file_put_contents('../'.$pluginIdentifier.'/'.$pluginIdentifier.'.php', $twig->render('plugin.php.twig', array(
            'pluginIdentifier' => $pluginIdentifier,
            'pluginNamespace' => $pluginNamespace,
            'pluginName' => $pluginName
        )));

        file_put_contents('../'.$pluginIdentifier.'/src/'.$pluginNamespace.'/Controller/DefaultController.php', $twig->render('src/DefaultController.php.twig', array(
            'pluginNamespace' => $pluginNamespace,
        )));

        file_put_contents('../'.$pluginIdentifier.'/src/'.$pluginNamespace.'/Entity/Person.php', $twig->render('src/Person.php.twig', array(
            'pluginIdentifier' => $pluginIdentifier,
            'pluginNamespace' => $pluginNamespace,
        )));

        file_put_contents('../'.$pluginIdentifier.'/src/'.$pluginNamespace.'/Form/Type/PersonType.php', $twig->render('src/PersonType.php.twig', array(
            'pluginNamespace' => $pluginNamespace,
        )));

        // copy files
        $fs->copy($templatesDirectory.'/resources/views/index.html.twig', '../'.$pluginIdentifier.'/resources/views/Default/index.html.twig');
        $fs->copy($templatesDirectory.'/resources/views/create.html.twig', '../'.$pluginIdentifier.'/resources/views/Default/create.html.twig');
        $fs->copy($templatesDirectory.'/resources/views/edit.html.twig', '../'.$pluginIdentifier.'/resources/views/Default/edit.html.twig');

        $output->writeln('Created "'.$pluginName.'" plugin in "'.$pluginIdentifier.'".');
        $output->writeln('Please ensure both "'.$pluginIdentifier.'/data/cache" and "'.$pluginIdentifier.'/data/proxies" exist and are writable by Apache\'s user.');
        $output->writeln('To finish, you need to activate your plugin in "Plugins".');
    }
}