<?php

namespace App\Manager;

use App\Entity\Revision;
use App\Message\CreateRevisionMessage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class RevisionManager
 * @package App\Manager
 */
class RevisionManager implements RevisionManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(EntityManagerInterface $em, MessageBusInterface $messageBus)
    {
        /** @var EntityManager em */
        $this->em = $em;
        $this->messageBus = $messageBus;
    }


    /**
     * @param string $className
     * @param int|null $id
     * @return mixed
     */
    public function getByEntityClassNameAndId(string $className, int $id = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em->createQueryBuilder();

        $qb->select('revision')
            ->from(Revision::class, 'revision')
            ->andWhere('revision.entityClass = :className')
            ->setParameter('className', $className);
        if (null !== $id) {
            $qb->andWhere('revision.entityId = :id')
                ->setParameter('id', $id);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $className
     * @param int $questionId
     * @param array $changeSet
     * @param \Datetime $updatedtAt
     */
    public function createRevisionMessage(string $className, int $entityId, array $changeSet, \Datetime $updatedtAt ): void
    {
        $this->messageBus->dispatch(new CreateRevisionMessage($className, $entityId, $changeSet, $updatedtAt ));
    }

    /**
     * @param CreateRevisionMessage $createRevisionMessage
     */
    public function processRevisionMessage(CreateRevisionMessage $createRevisionMessage)
    {
        $revision = new Revision();
        $revision->setEntityClass($createRevisionMessage->getEntityClassName());
        $revision->setEntityId($createRevisionMessage->getEntityId());
        $revision->setEntityUpdateDate($createRevisionMessage->getEntityUpdateDate());
        $revision->setUpdatedContent((array) $createRevisionMessage->getUpdatedContent());

        $this->em->persist($revision);
        $this->em->flush();
    }

}
