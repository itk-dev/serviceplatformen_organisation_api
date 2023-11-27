<?php

namespace App\Service\SF1500;

interface DataFetcherInterface
{
    public function fetch(int $pageSize, int $max): void;

    public function clientSoeg(array $options = []);

    public function clientList(array $options = []);
}
