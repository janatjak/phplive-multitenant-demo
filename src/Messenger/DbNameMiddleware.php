<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Repository\DbNameHolder;
use LogicException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ConsumedByWorkerStamp;

class DbNameMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if ($envelope->last(ConsumedByWorkerStamp::class) === null) {
            $dbname = DbNameHolder::getName();
            if ($dbname === null) {
                throw new LogicException('Missing dbname');
            }
            $envelope = $envelope->with(new DbNameStamp($dbname));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
