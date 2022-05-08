<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Messenger\DbNameStamp;
use App\Repository\DbNameHolder;
use RuntimeException;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Messenger\Command\ConsumeMessagesCommand;
use Symfony\Component\Messenger\Event\WorkerMessageReceivedEvent;

class WorkspaceEventSubscriber implements EventSubscriberInterface
{
    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $workspace = $event->getRequest()->get('workspace');
        $this->setWorkspace($workspace);
    }

    public function onConsole(ConsoleCommandEvent $event): void
    {
        if ($event->getCommand() instanceof ConsumeMessagesCommand) {
            return;
        }

        $workspace = $_ENV['WORKSPACE'] ?? getenv('WORKSPACE');
        $this->setWorkspace($workspace ?: '');
    }

    public function onWorker(WorkerMessageReceivedEvent $event): void
    {
        $envelope = $event->getEnvelope();
        $workspaceDbNameStamp = $envelope->last(DbNameStamp::class);
        if (!$workspaceDbNameStamp instanceof DbNameStamp) {
            throw new RuntimeException('Missing ' . DbNameStamp::class);
        }

        DbNameHolder::setName($workspaceDbNameStamp->dbName);
    }

    private function setWorkspace(string $workspace): void
    {
        $workspaces = json_decode(file_get_contents(__DIR__ . '/../../config/workspaces.json'), true, 512, JSON_THROW_ON_ERROR);
        if (!in_array($workspace, $workspaces, true)) {
            throw new RuntimeException("Invalid workspace: '$workspace'");
        }

        DbNameHolder::setName("php_live_$workspace");
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onRequest',
            ConsoleCommandEvent::class => 'onConsole',
            WorkerMessageReceivedEvent::class => 'onWorker',
        ];
    }
}
