<?php

namespace App\Tests\Command;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class HistoryExportCommandTest extends KerneltestCase
{

    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:history:export');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
                'entityClass' => 'foo'
            ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('no file generated', $output);

        $commandTester->execute([
            'entityClass' => 'App\Entity\Question'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('csv', $output);

        $commandTester->execute([
            'entityClass' => 'App\Entity\Question',
            'entityId' => 1
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('csv', $output);

        $commandTester->execute([
            'entityClass' => 'App\Entity\Question',
            'entityId' => 10
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('no file generated', $output);
    }

}
