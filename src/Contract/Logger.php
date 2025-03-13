<?php

declare(strict_types=1);

namespace Mate\Logger\Contract;

use Stringable;

interface Logger
{
    public function log($level, string|Stringable $message, array $context = []): void;
    public function setHandler(Handler $handler): self;
    public function getHandlers(): array;
    public function setChannel(string $channel): self;
    public function getChannel(): string;
}
