<?php
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Sf2PluginBase\Command\CreatePluginCommand;

require_once __DIR__.'/../../../includes/bootstrap.php';

class CreatePluginCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new CreatePluginCommand());
        $fs = new Filesystem();
        $command = $application->find('plugin:create');
        $commandTester = new CommandTester($command);

        $testPluginIdentifier = 'test-plugin';
        $commandTester->execute(array('command' => $command->getName(), 'pluginIdentifier' => $testPluginIdentifier));

        $testPluginDirectory = __DIR__.'/../../../'.$testPluginIdentifier;
        $this->assertTrue($fs->exists($testPluginDirectory));

        $fileContent = file_get_contents($testPluginDirectory.'/doctrine-config.php');
        $this->assertContains('$entityPaths = array(\'../test-plugin/src/TestPlugin/Entity\');', $fileContent);

        // cleanup
        $fs->remove($testPluginDirectory);
    }
}
?>
