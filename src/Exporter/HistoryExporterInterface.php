<?php


namespace App\Exporter;


interface HistoryExporterInterface
{
    public function export(string $entityClassName, int $entityId = null);
}