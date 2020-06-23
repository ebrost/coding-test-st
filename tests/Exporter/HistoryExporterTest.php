<?php

namespace App\Tests\Exporter;

use App\Exporter\HistoryExporterInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HistoryExporterTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    private $historyExporter;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->historyExporter = $container->get(HistoryExporterInterface::class);
    }


    public function testExport()
    {
        $filename = $this->historyExporter->export('App\Entity\Question', 1);
        $this->assertNotNull($filename);
    }
}