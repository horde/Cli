#!/usr/bin/env php
<?php

/**
 * Modern color demonstration using Horde\Cli\Cli (PSR-4)
 *
 * @package Cli
 */

// Bootstrap autoloader
$autoloadPaths = [
    __DIR__ . '/../../../vendor/autoload.php',
    __DIR__ . '/../../../lib/Horde/Cli.php',
];

foreach ($autoloadPaths as $autoload) {
    if (file_exists($autoload)) {
        require_once $autoload;
        break;
    }
}

// Use modern PSR-4 Cli class
$cli = new Horde\Cli\Cli();

/* Explicit colors using modern Cli */
$cli->writeln($cli->red('Red'));
$cli->writeln($cli->yellow('Yellow'));
$cli->writeln($cli->green('Green'));
$cli->writeln($cli->blue('Blue'));
$cli->writeln($cli->color('magenta', 'Magenta'));
$cli->writeln($cli->color('cyan', 'Cyan'));
$cli->writeln($cli->color('lightgray', 'Light Gray'));
$cli->writeln();

/* These messages are automatically colorized based on the message type. */
$cli->message('test', 'cli.error');
$cli->message('test', 'cli.warning');
$cli->message('test', 'cli.success');
$cli->message('test', 'cli.message');
$cli->writeln();

/* Modern presentation layer with semantic output */
$presenter = $cli->getPresenter();
$cli->writeln('=== Modern Presentation Layer ===');
$presenter->ok('Success message');
$presenter->warn('Warning message');
$presenter->info('Info message');
$presenter->error('Error message');
$cli->writeln();

/* Semantic categories */
$cli->writeln('=== Semantic Categories ===');
$presenter->semantic('detected', 'Found tool');
$presenter->semantic('created', 'File created');
$presenter->semantic('validation', 'Validation failed');
$presenter->semantic('improvement', 'Quality improved');
