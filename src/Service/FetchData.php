<?php

namespace App\Service;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Response;

class FetchData
{
    use LoggerAwareTrait;

    public function __construct(private readonly FetchServiceFactory $fetchServiceFactory)
    {
        $this->setLogger(new NullLogger());
    }

    public function fetch($pageSize = 1000, $max = null): Response
    {
        $this->logger->debug('Fetching data');

        // Person data.
//        $personService = $this->fetchServiceFactory->personService($this->logger);
//        $personService->fetch($pageSize, $max);

        // Bruger data.
//        $brugerService = $this->fetchServiceFactory->brugerService($this->logger);
//        $brugerService->fetch($pageSize, $max);


        return new Response(
            '<html lang="en"><body>Lucky number: '.'</body></html>'
        );
    }

}