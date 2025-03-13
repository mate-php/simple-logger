<?php

use Mate\Logger\Handler\FileHandler;
use Mate\Logger\Level;

describe('Build Handler', function () {
    test('default constructor', function () {
        $handler = new FileHandler("/tmp");

        $class = new ReflectionClass(FileHandler::class);
        $outputProperty = $class->getProperty("output");
        $levelProperty = $class->getProperty("level");
        $filenameProperty = $class->getProperty("fileName");

        $expected = "/tmp/log_" . date('Y_m_d') . ".log";

        expect($outputProperty->getValue($handler))
            ->toBe($expected);

        expect($levelProperty->getValue($handler))
            ->toBe(Level::Debug);

        expect($filenameProperty->getValue($handler))
            ->toBe('log');
    });

    test('constructor with int level', function () {
        $handler = new FileHandler("/tmp", 1);

        $class = new ReflectionClass(FileHandler::class);
        $levelProperty = $class->getProperty("level");

        expect($levelProperty->getValue($handler))
            ->toBe(Level::Debug);
    });

    test('constructor with string level', function () {
        $handler = new FileHandler("/tmp", 'alert');

        $class = new ReflectionClass(FileHandler::class);
        $levelProperty = $class->getProperty("level");

        expect($levelProperty->getValue($handler))
            ->toBe(Level::Alert);
    });

    test('constructor with Level enum', function () {
        $handler = new FileHandler("/tmp", Level::Alert);

        $class = new ReflectionClass(FileHandler::class);
        $levelProperty = $class->getProperty("level");

        expect($levelProperty->getValue($handler))
            ->toBe(Level::Alert);
    });

    test('set level', function () {
        $handler = new FileHandler("/tmp", Level::Alert);

        $class = new ReflectionClass(FileHandler::class);
        $levelProperty = $class->getProperty("level");

        expect($levelProperty->getValue($handler))
            ->toBe(Level::Alert);
    });

    test('set filename', function () {
        $handler = new FileHandler("/tmp", Level::Alert, 'myfile');

        $class = new ReflectionClass(FileHandler::class);
        $filenameProperty = $class->getProperty("fileName");

        expect($filenameProperty->getValue($handler))
            ->toBe('myfile');
    });
});
