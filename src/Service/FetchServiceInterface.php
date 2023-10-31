<?php

namespace App\Service;

interface FetchServiceInterface
{
    public function fetch(int $pageSize, int $max): void;

    function clientSoeg(array $options = []);

    function clientList(array $options = []);
}