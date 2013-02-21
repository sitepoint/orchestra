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
 
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Orchestra\Command\CreatePluginCommand;

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
        $this->assertContains('src/TestPlugin/Entity', $fileContent);

        // cleanup
        $fs->remove($testPluginDirectory);
    }
}
?>
