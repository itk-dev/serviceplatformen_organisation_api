<?php

namespace App\Command;

use App\Kernel;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'filesystem:cache:clear',
    description: 'Clear filessystem cache',
)]
class ClearFilesystemCacheCommand extends Command
{
    public function __construct(
        private Kernel $kernel
    )
    {
        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $environment = $this->kernel->getEnvironment();
        if (!in_array($environment, ['dev', 'test'], true)) {
            if (!$input->getOption('force')) {
                throw new RuntimeException(sprintf('This command should only be run in the dev and test environments. Use --force to run it in the %s environment.', $environment));
            }
        }

        $cache = new FilesystemAdapter();
        $output->writeln($cache->clear() ?  'Successfully cleared filesystem cache' : 'Failed clearing filesystem cache');
        return Command::SUCCESS;
    }
}
