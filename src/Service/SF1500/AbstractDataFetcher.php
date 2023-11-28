<?php

namespace App\Service\SF1500;

use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;

abstract class AbstractDataFetcher
{
    use LoggerAwareTrait;

    protected const DATA_TYPE = null;

    public function __construct(protected readonly EntityManagerInterface $entityManager, protected readonly SF1500Service $sf1500Service)
    {
    }

    public function fetch(int $pageSize, int $max)
    {
        $total = 0;

        $this->preFetchData();

        while (true) {
            $this->logFetchProgress(static::DATA_TYPE, $total, $max);

            $dataSize = $this->fetchData($pageSize, $total, $max);

            if (false === $dataSize) {
                break;
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
            gc_collect_cycles();

            $total += $dataSize;

            if ($total >= $max) {
                break;
            }
        }

        $this->logFetchFinished(static::DATA_TYPE);
    }

    protected function preFetchData(): void
    {
    }

    abstract protected function fetchData(int $pageSize, int $total, int $max): int|bool;

    abstract public function clientSoeg(array $options = []);

    abstract public function clientList(array $options = []);

    protected function logFetchProgress(string $dataType, int $total, int $max): void
    {
        $this->logger->debug(sprintf('Fetching %s data, offset: %d , max: %d', $dataType, $total, $max));
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
