<?php

declare(strict_types=1);

namespace Mate\Logger;

use Mate\Logger\Contract\Handler;
use Psr\Log\LoggerTrait;
use Stringable;

final class Logger
{
    use LoggerTrait;

    private const DEFAULT_CHANNEL = 'default';

    private array $handlers = [];

    public function __construct(
        private string $channel = self::DEFAULT_CHANNEL,
    ) {
    }

    public function setHandler(Handler $handler): self
    {
        $this->handlers[] = $handler;
        return $this;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function log($level, string|Stringable $message, array $context = []): void
    {
        $loggerLevel = Level::fromName($level);
        foreach ($this->handlers as $handler) {
            $handler->write($this->channel, $loggerLevel, $message, $context);
        }
        return;
    }
}
