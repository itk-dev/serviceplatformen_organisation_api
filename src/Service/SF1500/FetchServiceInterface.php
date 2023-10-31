<?php

namespace App\Service\SF1500;

interface FetchServiceInterface
{
    public function fetch(int $pageSize, int $max): void;

    function clientSoeg(array $options = []);

    function clientList(array $options = []);
}