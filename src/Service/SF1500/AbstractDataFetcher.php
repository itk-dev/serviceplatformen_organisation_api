<?php

namespace App\Service\SF1500;

use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;

abstract class AbstractDataFetcher
{
    use LoggerAwareTrait;

    public function __construct(protected readonly EntityManagerInterface $entityManager, protected readonly SF1500Service $sf1500Service)
    {
    }

    abstract public function fetch(int $pageSize, int $max): void;

    protected function logFetchProgress(string $dataType, int $total, int $max): void
    {
        $this->logger->debug(sprintf('Fetching %s data, offset: %d , max: %d', $dataType, $total, $max));
    }

    protected function logMemoryUsage(): void
    {
        $this->logger->debug(sprintf('Memory used: %s ', $this->convertFilesize(memory_get_usage())));
    }

    protected function logFetchFinished(string $dataType): void
    {
        $this->logger->debug(sprintf('Finished fetching %s data', $dataType));
    }

    // Converts filesize.
    protected function convertFilesize($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).$units[$pow];
    }
}
