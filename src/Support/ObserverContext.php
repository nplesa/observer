<?php

namespace nplesa\observer\Support;

class ObserverContext
{
    protected static ?int $userId = null;

    public static function setUserId(?int $id): void
    {
        self::$userId = $id;
    }

    public static function getUserId(): ?int
    {
        if (self::$userId !== null) {
            return self::$userId;
        }

        return auth()->check() ? auth()->id() : null;
    }

    public static function clear(): void
    {
        self::$userId = null;
    }
}
