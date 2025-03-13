<?php

declare(strict_types=1);

namespace Mate\Logger\Handler;

use JsonSerializable;
use Mate\Logger\Contract\Handler;
use Mate\Logger\Level;
use Mate\Logger\LoggerException;

final class FileHandler implements Handler
{
    private string $output = '';
    private ?bool $outputExists = null;

    public function __construct(
        private string $logDirectory,
        private int|string|Level $level = Level::Debug,
        private string $fileName = 'log'
    ) {
        $this->setLevel($level);
        $this->renderFileName();
    }

    public function setLevel(int|string|Level $level): self
    {
        if (is_int($this->level)) {
            $this->level = Level::fromValue($this->level);
            return $this;
        } elseif (is_string($this->level)) {
            $this->level = Level::fromName($this->level);
            return $this;
        }
        $this->level = $level;

        return $this;
    }

    private function renderFileName(): void
    {
        $this->output = $this->logDirectory
            . '/' . $this->fileName . '_' . date('Y_m_d') . '.log';
    }

    private function createOutputFileIfNotExists(): void
    {
        if ($this->outputExists === true) {
            return;
        }

        if (!file_exists($this->output)) {
            if (touch($this->output) === false) {
                throw new LoggerException(
                    sprintf('Cannot create log file: %s', $this->output),
                );
            }
            $this->outputExists = true;
        }
    }

    public function write(
        string $channel,
        Level $level,
        string $message,
        array|JsonSerializable $context = []
    ): void {
        if (!$this->isValidLevel($level)) {
            return;
        }

        $this->createOutputFileIfNotExists();

        if (
            file_put_contents(
                $this->output,
                $this->format($channel, $level, $message, $context),
                FILE_APPEND | LOCK_EX
            ) === false
        ) {
            throw new LoggerException(
                sprintf('Unable to write to the log file: %s', $this->output),
            );
        }
    }

    private function format(
        string $channel,
        Level $level,
        string $message,
        array|JsonSerializable $context
    ): string {
        return '[' . date('Y-m-d H:i:s') . '] [' . $level->getName() . '] [' . $channel . '] '
            . $message
            . ' '
            . json_encode($context)
            . PHP_EOL;
    }

    private function isValidLevel(Level $level): bool
    {
        return $this->level->value <= $level->value;
    }
}
