<?php

namespace App\Tests\MessageHandler;

use App\Manager\RevisionManagerInterface;
use App\Message\CreateRevisionMessage;
use App\MessageHandler\CreateRevisionMessageHandler;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

class CreaterevisionMessageHandlerTest extends TestCase
{

   public function testProcessRevisionMessage(): void
   {
       $createRevisionMessage = new CreateRevisionMessage('revisionMessageTest', 1000, ['test'=>'test'], new \DateTime() );

       $revisionManagerMock = $this->getMockBuilder(RevisionManagerInterface::class)
           ->disableOriginalConstructor()
           ->getMock();

       $revisionManagerMock->expects($this->once())
           ->method('processRevisionMessage')
           ->with($createRevisionMessage);

       $createRevisionMessageHandler = new CreateRevisionMessageHandler($revisionManagerMock);

       // call as a function
       $createRevisionMessageHandler($createRevisionMessage);
   }

}