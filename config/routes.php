<?php

declare(strict_types=1);

return static function (\Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->import('../src/Controller/', 'annotation');
};
