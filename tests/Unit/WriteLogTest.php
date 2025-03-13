<?php

use Mate\Logger\Handler\FileHandler;
use Mate\Logger\Level;
use Mate\Logger\Logger;
use Mate\Logger\LoggerException;

function removeOutputFile(string $output): void
{
    if (file_exists($output)) {
        unlink($output);
    }
}

describe('Write log', function () {
    test('write valid level log', function () {
        $handler = new FileHandler("/tmp");
        $logger = new Logger();
        $logger->setHandler($handler);

        $class = new ReflectionClass(FileHandler::class);
        $outputProperty = $class->getProperty("output");
        $output = $outputProperty->getValue($handler);
        removeOutputFile($output);

        $logger->debug("test");
        $content = file_get_contents($output);

        expect($content)
            ->toContain('[DEBUG] [default] test []');

        removeOutputFile($output);
    });

    test('write invalid level log', function () {
        $handler = new FileHandler("/tmp", Level::Alert);
        $logger = new Logger();
        $logger->setHandler($handler);

        $class = new ReflectionClass(FileHandler::class);
        $outputProperty = $class->getProperty("output");
        $output = $outputProperty->getValue($handler);
        removeOutputFile($output);

        $logger->alert("alert message");
        $logger->debug("debug message");
        $content = file_get_contents($output);

        expect($content)
            ->toContain('[ALERT] [default] alert message []');

        removeOutputFile($output);
    });

    test('dont create log file if invalid level log', function () {
        $handler = new FileHandler("/tmp", Level::Alert);
        $logger = new Logger();
        $logger->setHandler($handler);

        $class = new ReflectionClass(FileHandler::class);
        $outputProperty = $class->getProperty("output");
        $output = $outputProperty->getValue($handler);
        removeOutputFile($output);

        $logger->debug("test");

        expect(file_exists($output))
            ->toBeFalse();

        removeOutputFile($output);
    });

    test('no create log file if exists', function () {
        $handler = new FileHandler("/tmp");
        $logger = new Logger();
        $logger->setHandler($handler);

        $class = new ReflectionClass(FileHandler::class);
        $outputProperty = $class->getProperty("output");
        $output = $outputProperty->getValue($handler);
        removeOutputFile($output);

        $logger->debug("debug message 1");
        $logger->debug("debug message 2");

        $content = file_get_contents($output);

        expect($content)
            ->toContain('[DEBUG] [default] debug message 1 []');

        expect($content)
            ->toContain('[DEBUG] [default] debug message 2 []');

        removeOutputFile($output);
    });

    test('cannot create log file', function () {
        $handler = new FileHandler("/dummy");
        $logger = new Logger();
        $logger->setHandler($handler);

        $class = new ReflectionClass(FileHandler::class);
        $outputProperty = $class->getProperty("output");
        $output = $outputProperty->getValue($handler);
        removeOutputFile($output);

        $logger->debug("debug message");
    })->throws(LoggerException::class);

    test('log file without write permissions', function () {
        $handler = new FileHandler("/tmp");
        $logger = new Logger();
        $logger->setHandler($handler);

        $class = new ReflectionClass(FileHandler::class);
        $outputProperty = $class->getProperty("output");
        $output = $outputProperty->getValue($handler);
        removeOutputFile($output);

        touch($output);
        chmod($output, 0100);

        $logger->debug("debug message");

        removeOutputFile($output);
    })->throws(LoggerException::class);
});
