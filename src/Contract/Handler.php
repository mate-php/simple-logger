<?php

declare(strict_types=1);

namespace Mate\Logger\Contract;

use JsonSerializable;
use Mate\Logger\Level;

interface Handler
{
    public function write(
        string $channel,
        Level $level,
        string $message,
        array|JsonSerializable $context = []
    ): void;
}
