<?php

use Mate\Logger\Handler\FileHandler;
use Mate\Logger\Logger;

describe('Build Logger', function () {
    test('default constructor', function () {
        $logger = new Logger();

        expect($logger->getChannel())
            ->toBe("default");
    });

    test('constructor with channel', function () {
        $logger = new Logger("my channel");

        expect($logger->getChannel())
            ->toBe("my channel");
    });

    test('set channel', function () {
        $logger = new Logger();
        $logger->setChannel("my channel");

        expect($logger->getChannel())
            ->toBe("my channel");
    });

    test('set handler', function () {
        $handler = new FileHandler("/tmp");
        $logger = new Logger("my channel");
        $logger->setHandler($handler);

        $handlers = $logger->getHandlers();

        expect($handlers)
            ->toBeArray();

        expect($handlers)->toHaveCount(1);

        expect($handlers[0])
            ->toBe($handler);
    });
});
