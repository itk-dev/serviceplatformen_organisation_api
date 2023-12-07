<?php

namespace App\Service\SF1500;

use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;

abstract class AbstractDataFetcher
{
    use LoggerAwareTrait;

    protected const DATA_TYPE = null;
    private int $start = 0;

    public function __construct(protected readonly EntityManagerInterface $entityManager, protected readonly SF1500Service $sf1500Service)
    {
    }

    /**
     * Fetches data.
     */
    public function fetch(int $pageSize, int $max): void
    {
        $this->start = strtotime('now');
        $total = 0;

        $this->preFetchData();

        while (true) {
            $this->logFetchProgress($total, $max);

            $dataSize = $this->fetchData($pageSize, $total, $max);

            if ($dataSize < 1) {
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

        $this->postFetchData();

        $this->logFetchFinished($total);
    }

    /**
     * Method for adding logic prior to fetchData, e.g. setting up resources or variables.
     */
    protected function preFetchData(): void
    {
    }

    /**
     * Method for adding logic post fetchData, e.g. cleaning up resources or variables.
     */
    protected function postFetchData(): void
    {
    }

    /**
     * Method should fetch next chunk of data and persist them via the entity manager.
     *  Flushing is handled by the fetch method @see AbstractDataFetcher::fetch().
     *
     * @return int the number of fetched records or -1
     */
    abstract protected function fetchData(int $pageSize, int $total, int $max): int;

    /**
     * Sets up client for Soeg purposes.
     */
    abstract public function clientSoeg(array $options = []);

    /**
     * Sets up client for List purposes.
     */
    abstract public function clientList(array $options = []);

    /**
     * Logs fetch progress.
     */
    protected function logFetchProgress(int $total, int $max): void
    {
        $this->logger->debug(sprintf('Fetching %s data, offset: %d , max: %d', static::DATA_TYPE, $total, $max));
        $this->logger->debug(sprintf('Memory used: %s ', $this->convertFilesize(memory_get_usage())));
    }

    /**
     * Logs fetch finished.
     */
    protected function logFetchFinished(int $total): void
    {
        $this->logger->debug(sprintf('Finished fetching %s data.', static::DATA_TYPE));
        $this->logger->debug(sprintf('Fetched a total of %d records.', $total));
        $this->logger->debug(sprintf('Elapsed time: %s', gmdate('H:i:s', strtotime('now') - $this->start)));
    }

    /**
     * Converts filesize.
     */
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
