#!/usr/bin/env php
<?php

/**
 * Quick test script to verify presenter integration.
 */

// Use composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// Test with legacy Horde_Cli
echo "=== Testing Legacy Horde_Cli ===\n";
$cli = new Horde_Cli();
$presenter = $cli->getPresenter(['cli_format' => 'classic']);

$presenter->ok('Success message');
$presenter->warn('Warning message');
$presenter->info('Info message');
$presenter->error('Error message');
$presenter->plain('');

$presenter->semantic('detected', 'Found configuration file');
$presenter->semantic('created', 'Generated output.txt');
$presenter->semantic('validation', 'Schema check failed');
$presenter->plain('');

// Test with modern Horde\Cli\Cli
echo "=== Testing Modern Horde\\Cli\\Cli ===\n";
$modernCli = new Horde\Cli\Cli();
$modernPresenter = $modernCli->getPresenter(['cli_format' => 'unicode']);

$modernPresenter->ok('Success message');
$modernPresenter->warn('Warning message');
$modernPresenter->info('Info message');
$modernPresenter->error('Error message');
$modernPresenter->plain('');

$modernPresenter->semantic('detected', 'Found configuration file');
$modernPresenter->semantic('created', 'Generated output.txt');
$modernPresenter->semantic('validation', 'Schema check failed');

echo "\n✅ All tests passed!\n";
