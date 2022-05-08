<?php

declare(strict_types=1);

namespace App\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TestMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(TestMessage $message): void
    {
        dump((string) $this->entityManager->getConnection()->getParams()['dbname']);
        dump($message->message);
    }
}
