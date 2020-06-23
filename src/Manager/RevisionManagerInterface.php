<?php


namespace App\Manager;


use App\Message\CreateRevisionMessage;

interface RevisionManagerInterface
{
    /**
     * @param string $className
     * @param int|null $id
     * @return mixed
     */
    public function getByEntityClassNameAndId(string $className, int $id = null);

    /**
     * @param string $className
     * @param int $questionId
     * @param array $changeSet
     * @param \Datetime $updatedtAt
     */
    public function createRevisionMessage(
        string $className,
        int $entityId,
        array $changeSet,
        \Datetime $updatedtAt
    ): void;

    /**
     * @param CreateRevisionMessage $createRevisionMessage
     */
    public function processRevisionMessage(CreateRevisionMessage $createRevisionMessage);
}