<?php

declare(strict_types=1);

namespace App\Messenger;

use JetBrains\PhpStorm\Immutable;
use Symfony\Component\Messenger\Stamp\StampInterface;

#[Immutable]
class DbNameStamp implements StampInterface
{
    public function __construct(public string $dbName)
    {
    }
}
