<?php

namespace App\Exporter;

use App\Exception\HistoryExporterException;
use App\Manager\RevisionManagerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class HistoryExporter
 * @package App\Exporter
 */
class HistoryCsvExporter implements  HistoryExporterInterface
{
    /**
     * @var RevisionManagerInterface
     */
    private $revisionManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(RevisionManagerInterface $revisionManager, SerializerInterface $serializer)
    {
        $this->revisionManager = $revisionManager;
        $this->serializer = $serializer;
    }

    /**
     * @param string $entityClassName
     * @param int|null $entityId
     * @return string|null
     * @throws HistoryExporterException
     * @throws \ReflectionException
     */
    public function export(string $entityClassName, int $entityId = null): ?string
    {
        $shortClassName = (new \ReflectionClass('App\Entity\Question'))->getShortName();
        $filepath = tempnam(sys_get_temp_dir(), $shortClassName.'_export_').'.csv';
        try{
            $revision = $this->revisionManager->getByEntityClassNameAndId($entityClassName, $entityId);

            if (!empty($revision)) {
                $revisionToCsv = $this->serializer->serialize($revision, 'csv', [CsvEncoder::DELIMITER_KEY => ';']);
                file_put_contents(
                    $filepath,
                    $revisionToCsv,
                    FILE_USE_INCLUDE_PATH
                );

                return $filepath;
            }
        }
        catch (ExceptionInterface $e){

                throw new HistoryExporterException('History Exporter Exception : unserializable content');
        }
        return null;
    }
}
