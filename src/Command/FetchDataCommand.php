<?php

namespace App\Command;

use App\Service\FetchData;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'organisation:fetch:data',
    description: 'Add a short description for your command',
)]
class FetchDataCommand extends Command
{
    public function __construct(private readonly FetchData $fetchData)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pageSize = 1000;
        $max = 5000;

        $this->fetchData->setLogger(new ConsoleLogger($output));

        $this->fetchData->fetch($pageSize, $max);

        return Command::SUCCESS;
    }
}
