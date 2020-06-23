<?php

namespace App\Command;

use App\Exporter\HistoryCsvExporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HistoryExportCommand extends Command
{
    protected static $defaultName = 'app:history:export';
    /**
     * @var HistoryCsvExporter
     */
    private $historyExporter;

    public function __construct(HistoryCsvExporter $historyExporter)
    {
        $this->historyExporter = $historyExporter;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Export revision history for an entity')
            ->addArgument('entityClass', InputArgument::REQUIRED, 'The fully qualified entity name with namespace (required)')
            ->addArgument('entityId', InputArgument::OPTIONAL, 'The id of the entity (optional)')
            ->setHelp('This command export the history of an entity (edited fields) to a csv file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityClassName = $input->getArgument('entityClass');
        $entityId = $input->getArgument('entityId');

        if(!class_exists($entityClassName))
        {
            $output->writeln(sprintf('<comment>class %s does not exists. Did you forget to escape the namespace ( ex App\\Entity\\MyEntity )?</comment>', $entityClassName));
        }


        try{
            $filePath = $this->historyExporter->export($entityClassName, $entityId);
        }
        catch (\Exception $e){
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return -1;
        }

        if ($filePath) {
            $output->writeln(sprintf('<info>export file generated : %s</info>', $filePath));
        } else {
            $output->writeln('<comment>no file generated</comment>');
        }
        return 0;
    }
}
