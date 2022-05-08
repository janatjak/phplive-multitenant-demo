<?php

declare(strict_types=1);

namespace App\Messenger;

class TestMessage
{
    public function __construct(public readonly string $message)
    {
    }
}
