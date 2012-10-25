<?php
namespace Sf2PluginBase\Command;

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
            $fs->mkdir(array($pluginIdentifier, $pluginIdentifier.'/views', $pluginIdentifier.'/src', $pluginIdentifier.'/src/'.$pluginNamespace), 0766);
            $fs->mkdir($pluginIdentifier.'/cache');
        } catch (IOException $e) {
            $output->writeln('An error occured while creating directories: '.$e->getMessage());
        }

        // generate files
        $loader = new \Twig_Loader_Filesystem(__DIR__.'/../../../templates');
        $twig = new \Twig_Environment($loader);

        file_put_contents($pluginIdentifier.'/cli-config.php', $twig->render('cli-config.php.twig', array()));

        file_put_contents($pluginIdentifier.'/doctrine-config.php', $twig->render('doctrine-config.php.twig', array(
            'pluginIdentifier' => $pluginIdentifier,
            'pluginNamespace' => $pluginNamespace
        )));

        file_put_contents($pluginIdentifier.'/'.$pluginIdentifier.'.php', $twig->render('plugin.php.twig', array(
            'pluginIdentifier' => $pluginIdentifier,
            'pluginNamespace' => $pluginNamespace,
            'pluginName' => $pluginName
        )));

        $output->writeln('Created "'.$pluginName.'" plugin in "'.$pluginIdentifier.'"');
    }
}