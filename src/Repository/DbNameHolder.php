<?php

declare(strict_types=1);

namespace App\Repository;

use LogicException;
use Stringable;

class DbNameHolder implements Stringable
{
    private static ?string $name = null;

    public static function setName(string $name): void
    {
        self::$name = $name;
    }

    public static function getName(): ?string
    {
        return self::$name;
    }

    public function __toString(): string
    {
        if (self::$name === null) {
            throw new LogicException('Missing workspace dbname');
        }

        return self::$name;
    }

    /**
     * Required for multi-tenant doctrine result cache key
     *
     * @return array<mixed>
     */
    public function __serialize(): array
    {
        return [
            'name' => self::$name,
        ];
    }

}
