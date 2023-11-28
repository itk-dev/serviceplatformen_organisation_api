<?php

namespace App\Command;

use App\Service\FetchData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'organisation:fetch:data',
    description: 'Fetch organisation data',
)]
class FetchDataCommand extends Command
{
    public const ALLOWED_DATA_TYPES = [
        'person',
        'bruger',
        'adresse',
        'organisationfunktion',
        'organisationenhed',
    ];

    public function __construct(private readonly FetchData $fetchData)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $dataTypeDescription = sprintf('Which data to fetch. If no data type(s) is provided it will fetch all by default. Allowed options: %s.', implode(', ', self::ALLOWED_DATA_TYPES));

        $this
            ->addArgument('data-type', InputArgument::IS_ARRAY, $dataTypeDescription)
            ->addOption('page-size', null, InputOption::VALUE_REQUIRED, 'Page size, e.g. number of elements fetched per call.', 1000)
            ->addOption('max', null, InputOption::VALUE_REQUIRED, 'Number of elements to fetch', PHP_INT_MAX)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Handle data type option.
        $dataTypes = $input->getArgument('data-type') ?: self::ALLOWED_DATA_TYPES;

        // Check for duplicate entries
        $duplicated = array_diff_assoc($dataTypes, array_unique($dataTypes));

        if (!empty($duplicated)) {
            $output->writeln(sprintf('Duplicated data-types detected: %s.', implode(', ', $duplicated)));

            return Command::INVALID;
        }

        // Check for allowed datatypes.
        foreach ($dataTypes as $dataType) {
            if (!in_array($dataType, self::ALLOWED_DATA_TYPES)) {
                $output->writeln(sprintf('Data type: %s not allowed. Allowed data types are: %s.', $dataType, implode(', ', self::ALLOWED_DATA_TYPES)));

                return Command::INVALID;
            }
        }

        // Handle page size option.
        $pageSize = (int) $input->getOption('page-size');

        if ($pageSize < 1 || $pageSize > 1000) {
            $output->writeln(sprintf('Page size: %s not allowed. Page size should be an integer between 1 and 1000', $pageSize));

            return Command::INVALID;
        }

        // Handle max option.
        $max = (int) $input->getOption('max');

        if ($max < 1) {
            $output->writeln(sprintf('Max: %s not allowed. Max should be an integer greater than 1', $max));

            return Command::INVALID;
        }

        $this->fetchData->setLogger(new ConsoleLogger($output));
        $this->fetchData->fetch($dataTypes, $pageSize, $max);

        return Command::SUCCESS;
    }
}
