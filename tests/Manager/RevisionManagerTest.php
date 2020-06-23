<?php

namespace App\Tests\Manager;

use App\Manager\RevisionManager;
use App\Manager\RevisionManagerInterface;
use App\Message\CreateRevisionMessage;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RevisionManagerTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testCreateRevisionMessage()
    {
        $container = self::$container;
        $revisionManager = $container->get(RevisionManagerInterface::class);
        $revisionManager->createRevisionMessage('fake',1, [], new \DateTime());

        /** @var InMemoryTransport $transport */
        $transport = $container->get('messenger.transport.async');
        $this->assertCount(1, $transport->get());
    }

    public function testGetByEntityClassNameAndId()
    {
        $container = self::$container;
        $revisionManager = $container->get(RevisionManagerInterface::class);

        $revisions = $revisionManager->getByEntityClassNameAndId('App\Entity\Question');
        $this->assertEquals(5,count($revisions));

        $revisions = $revisionManager->getByEntityClassNameAndId('App\Entity\Question',1);
        $this->assertEquals(2,count($revisions));

        $revisions = $revisionManager->getByEntityClassNameAndId('App\Entity\Question',10);
        $this->assertEquals(0,count($revisions));
    }

    public function testProcessRevisionMessage()
    {
        $container = self::$container;
        $revisionManager = $container->get(RevisionManager::class);

        //before
        $revisions = $revisionManager->getByEntityClassNameAndId('App\Entity\Question',1);
        $beforeCount = count($revisions);

        $revisionMessage = new CreateRevisionMessage('App\Entity\Question',1,['title'=>'new title'],new \DateTime() );
        $revisionManager->processRevisionMessage($revisionMessage);

        //after
        $revisions = $revisionManager->getByEntityClassNameAndId('App\Entity\Question',1);
        $this->assertEquals($beforeCount+1,count($revisions));

    }
}
