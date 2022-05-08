<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;

class DynamicConnection extends Connection
{
    /**
     * @param array<mixed> $params
     */
    public function __construct(array $params, Driver $driver, ?Configuration $config, ?EventManager $eventManager)
    {
        unset($params['url']);

        // fix drop + create database
        if (isset($params['dbname'])) {
            $params['dbname'] = new DbNameHolder();
        }

        parent::__construct($params, $driver, $config, $eventManager);
    }
}
