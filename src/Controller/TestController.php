<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Test;
use App\Messenger\TestMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/{workspace}')]
    public function __invoke(string $workspace): JsonResponse
    {
        $message = $workspace . ' ' . date(DATE_ATOM);

        $this->bus->dispatch(new TestMessage($message));

        $test = new Test($message);
        $this->entityManager->persist($test);
        $this->entityManager->flush();

        return new JsonResponse([
            'dbname' => (string) $this->entityManager->getConnection()->getParams()['dbname'],
            'items' => $this->entityManager->getRepository(Test::class)->findAll(),
        ]);
    }
}
