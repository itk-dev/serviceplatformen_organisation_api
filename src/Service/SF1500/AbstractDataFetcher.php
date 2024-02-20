<?php

namespace App\Service\SF1500;

use App\Service\SF1500Service;
use Doctrine\ORM\EntityManagerInterface;
use ItkDev\Serviceplatformen\Service\Exception\SoapException;
use Psr\Log\LoggerAwareTrait;

abstract class AbstractDataFetcher
{
    use LoggerAwareTrait;

    protected const DATA_TYPE = null;
    private ?\DateTimeImmutable $start = null;

    public function __construct(protected readonly EntityManagerInterface $entityManager, protected readonly SF1500Service $sf1500Service)
    {
    }

    /**
     * Fetches data.
     */
    public function fetch(int $pageSize, int $max): void
    {
        $this->start = new \DateTimeImmutable();
        $total = 0;

        $this->preFetchData();

        while (true) {
            $this->logFetchProgress($total, $max);

            $dataSize = null;
            $retry = 0;

            while (null === $dataSize) {
                try {
                    $dataSize = $this->fetchData($pageSize, $total, $max);
                } catch (SoapException $exception) {
                    // If the same call has failed 5 times in a row stop.
                    if ($retry >= 5) {
                        $this->logger->error('Fetching data failed', [
                            'class' => self::class,
                            'exception_message' => $exception->getMessage(),
                            'exception_code' => $exception->getCode(),
                        ]);
                        throw $exception;
                    }
                }
                ++$retry;
            }

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
        $now = new \DateTimeImmutable();

        $this->logger->debug(sprintf('Fetching %s data, offset: %d , max: %d', static::DATA_TYPE, $total, $max));
        $this->logger->debug(sprintf('Memory used: %s ', $this->convertFilesize(memory_get_usage())));
        $this->logger->debug(sprintf('Elapsed time: %s', $now->diff($this->start)->format('%H:%I:%S')));
    }

    /**
     * Logs fetch finished.
     */
    protected function logFetchFinished(int $total): void
    {
        $now = new \DateTimeImmutable();

        $this->logger->debug(sprintf('Finished fetching %s data.', static::DATA_TYPE));
        $this->logger->debug(sprintf('Fetched a total of %d records.', $total));
        $this->logger->debug(sprintf('Elapsed time: %s', $now->diff($this->start)->format('%H:%I:%S')));
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
