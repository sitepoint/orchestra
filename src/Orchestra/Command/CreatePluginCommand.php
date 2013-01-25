<?php
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
                $pluginIdentifier,
                $pluginIdentifier.'/resources',
                $pluginIdentifier.'/resources/views',
                $pluginIdentifier.'/resources/public',
                $pluginIdentifier.'/data',
                $pluginIdentifier.'/data/cache',
                $pluginIdentifier.'/data/proxies',
                $pluginIdentifier.'/src',
                $pluginIdentifier.'/src/'.$pluginNamespace,
                $pluginIdentifier.'/src/'.$pluginNamespace.'/Controller',
                $pluginIdentifier.'/src/'.$pluginNamespace.'/Entity',
                $pluginIdentifier.'/src/'.$pluginNamespace.'/Form/Type'
            ));
        } catch (IOException $e) {
            $output->writeln('An error occured while creating directories: '.$e->getMessage());
        }

        $templatesDirectory = __DIR__.'/../../../templates';
        // generate files
        $loader = new \Twig_Loader_Filesystem($templatesDirectory);
        $twig = new \Twig_Environment($loader);

        file_put_contents($pluginIdentifier.'/doctrine-config.php', $twig->render('doctrine-config.php.twig', array(
            'pluginIdentifier' => $pluginIdentifier,
            'pluginNamespace' => $pluginNamespace
        )));

        file_put_contents($pluginIdentifier.'/'.$pluginIdentifier.'.php', $twig->render('plugin.php.twig', array(
            'pluginIdentifier' => $pluginIdentifier,
            'pluginNamespace' => $pluginNamespace,
            'pluginName' => $pluginName
        )));

        file_put_contents($pluginIdentifier.'/src/'.$pluginNamespace.'/Controller/DefaultController.php', $twig->render('src/DefaultController.php.twig', array(
            'pluginNamespace' => $pluginNamespace,
        )));

        file_put_contents($pluginIdentifier.'/src/'.$pluginNamespace.'/Entity/Person.php', $twig->render('src/Person.php.twig', array(
            'pluginIdentifier' => $pluginIdentifier,
            'pluginNamespace' => $pluginNamespace,
        )));

        file_put_contents($pluginIdentifier.'/src/'.$pluginNamespace.'/Form/Type/PersonType.php', $twig->render('src/PersonType.php.twig', array(
            'pluginNamespace' => $pluginNamespace,
        )));

        // copy files
        $fs->copy($templatesDirectory.'/resources/views/index.html.twig', $pluginIdentifier.'/resources/views/Default/index.html.twig');
        $fs->copy($templatesDirectory.'/resources/views/create.html.twig', $pluginIdentifier.'/resources/views/Default/create.html.twig');
        $fs->copy($templatesDirectory.'/resources/views/edit.html.twig', $pluginIdentifier.'/resources/views/Default/edit.html.twig');

        $output->writeln('Created "'.$pluginName.'" plugin in "'.$pluginIdentifier.'"');
        $output->writeln('To finish, you need to activate your plugin in "Plugins"');
    }
}