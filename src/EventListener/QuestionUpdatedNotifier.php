<?php

namespace App\EventListener;

use App\Entity\Question;
use App\Manager\RevisionManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;


/**
 * Class QuestionUpdatedNotifier
 * @package App\EventListener
 */
class QuestionUpdatedNotifier
{

    private $revisionManager;

    public function __construct(RevisionManagerInterface $revisionManager)
    {
        $this->revisionManager = $revisionManager;
    }

    public function preUpdate(Question $question, PreUpdateEventArgs $event): void
    {
        /** @var Question $updatedQuestion */
        $updatedQuestion = $event->getEntity();
        $questionChangeSet = $event->getEntityChangeSet();
        if (!empty($questionChangeSet)) {
            $this->revisionManager->createRevisionMessage(Question::class, $updatedQuestion->getId(), $questionChangeSet, $updatedQuestion->getUpdatedAt());
        }
    }
}

