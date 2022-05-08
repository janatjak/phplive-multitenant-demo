<?php

declare(strict_types=1);

use App\Messenger\DbNameMiddleware;
use App\Messenger\TestMessage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('framework', [
        'secret' => '%env(APP_SECRET)%',
        'http_method_override' => false,
        'session' => [
            'handler_id' => null,
            'cookie_secure' => 'auto',
            'cookie_samesite' => 'lax',
            'storage_factory_id' => 'session.storage.factory.native',
        ],
        'php_errors' => [
            'log' => true,
        ],
        // multitenant
        'messenger' => [
            'buses' => [
                'messenger.bus.default' => [
                    'middleware' => [
                        DbNameMiddleware::class,
                    ],
                ],
            ],
            'transports' => [
                'async' => '%env(MESSENGER_TRANSPORT_DSN)%',
            ],
            'routing' => [
                TestMessage::class => 'async',
            ],
        ]
    ]);
};
