<?php

namespace App\MessageHandler;

use App\Manager\RevisionManagerInterface;
use App\Message\CreateRevisionMessage;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class CreateRevisionMessageHandler
 * @package App\MessageHandler
 */
class CreateRevisionMessageHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var RevisionManagerInterface
     */
    private $revisionManager;

    public function __construct(RevisionManagerInterface $revisionManager)
    {
        $this->revisionManager = $revisionManager;
    }

    public function __invoke(CreateRevisionMessage $createRevisionMessage)
    {
        $this->revisionManager->processRevisionMessage($createRevisionMessage);
    }
}
