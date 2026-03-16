#!/usr/bin/env php
<?php
/**
 * Horde_Cli Presentation Layer Examples
 *
 * This example demonstrates the modern presentation layer with
 * three output formats: Unicode, Classic, and CI.
 *
 * Copyright 2017-2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category Horde
 * @package  Cli
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 */

// Bootstrap autoloader - adjust path as needed
$autoloadPaths = [
    __DIR__ . '/../../../vendor/autoload.php',  // Composer install
    __DIR__ . '/../../../lib/Horde/Cli.php',     // Fallback to legacy
];

foreach ($autoloadPaths as $autoload) {
    if (file_exists($autoload)) {
        require_once $autoload;
        break;
    }
}

// Create CLI instance using modern PSR-4 class
$cli = new Horde\Cli\Cli();

echo "\n" . str_repeat('=', 70) . "\n";
echo "Horde_Cli Presentation Layer Examples\n";
echo str_repeat('=', 70) . "\n\n";

// Example 1: Auto-detect format (recommended)
echo "Example 1: Auto-detect format\n";
echo str_repeat('-', 70) . "\n";
$presenter = $cli->getPresenter();

$presenter->ok('Task completed successfully!');
$presenter->warn('Deprecated method usage detected');
$presenter->info('Processing 142 files...');
$presenter->error('Configuration file not found');
echo "\n";

// Example 2: Explicit Unicode format (modern terminals)
echo "Example 2: Unicode format with emoji\n";
echo str_repeat('-', 70) . "\n";
$unicode = $cli->getPresenter(['cli_format' => 'unicode']);

$unicode->semantic('detected', 'Found PHPUnit configuration');
$unicode->semantic('running', 'Executing test suite');
$unicode->semantic('created', 'Generated coverage report');
$unicode->semantic('validation', 'Schema validation failed');
echo "\n";

// Example 3: Classic format (backward compatible)
echo "Example 3: Classic format with colored brackets\n";
echo str_repeat('-', 70) . "\n";
$classic = $cli->getPresenter(['cli_format' => 'classic']);

$classic->ok('Database migration complete');
$classic->warn('Table already exists, skipping');
$classic->info('Rolling back transaction');
$classic->error('Connection timeout');
echo "\n";

// Example 4: CI format (GitHub Actions, Jenkins, etc.)
echo "Example 4: CI format (plain labels, no colors)\n";
echo str_repeat('-', 70) . "\n";
$ci = $cli->getPresenter(['cli_format' => 'ci']);

$ci->ok('Build successful');
$ci->warn('Code coverage below threshold');
$ci->info('Deploying to staging');
$ci->error('Deployment failed');
echo "\n";

// Example 5: Semantic categories for rich context
echo "Example 5: Semantic categories\n";
echo str_repeat('-', 70) . "\n";
$presenter = $cli->getPresenter();

// File operations
$presenter->semantic('created', 'Created file: output.txt');
$presenter->semantic('updated', 'Updated file: config.php');
$presenter->semantic('deleted', 'Deleted file: cache.tmp');

// Git operations
$presenter->semantic('branch', 'Created branch: feat/new-feature');
$presenter->semantic('commit', 'Committed 5 files');
$presenter->semantic('push', 'Pushed to origin/main');
$presenter->semantic('tag', 'Tagged release: v2.0.0');

// Quality metrics
$presenter->semantic('metrics', 'Code coverage: 87.3%');
$presenter->semantic('improvement', 'Performance improved by 23%');
$presenter->semantic('regression', 'Memory usage increased by 15%');

// Process state
$presenter->semantic('skip', 'Skipping optional step');
$presenter->semantic('auto', 'Auto-corrected formatting');
$presenter->semantic('config', 'Configuration updated');
$presenter->semantic('release', 'Released version 2.0.0');
echo "\n";

// Example 6: Color methods
echo "Example 6: Direct color methods\n";
echo str_repeat('-', 70) . "\n";
$presenter->bold('This text is bold');
$presenter->blue('This text is blue');
$presenter->green('This text is green');
$presenter->yellow('This text is yellow');
echo "\n";

// Example 7: Unified semantic interface
echo "Example 7: Traditional log levels via semantic()\n";
echo str_repeat('-', 70) . "\n";
// You can use semantic() with traditional levels too
$presenter->semantic('ok', 'Success via semantic()');
$presenter->semantic('warn', 'Warning via semantic()');
$presenter->semantic('info', 'Info via semantic()');
$presenter->semantic('error', 'Error via semantic()');
echo "\n";

// Example 8: Disable colors
echo "Example 8: Disable colors with nocolor option\n";
echo str_repeat('-', 70) . "\n";
$nocolor = $cli->getPresenter(['cli_format' => 'unicode', 'nocolor' => true]);
$nocolor->ok('Success without colors');
$nocolor->semantic('created', 'File created (no colors)');
echo "\n";

// Example 9: Using with legacy Horde_Cli (backward compatible)
echo "Example 9: Using legacy Horde_Cli class\n";
echo str_repeat('-', 70) . "\n";
$legacyCli = new Horde_Cli();
$legacyPresenter = $legacyCli->getPresenter();
$legacyPresenter->info('Works with both legacy and modern Cli!');
echo "\n";

echo str_repeat('=', 70) . "\n";
echo "For more information, see:\n";
echo "  - Presenter interface: src/Output/Presenter.php\n";
echo "  - Unicode presenter: src/Output/Presenter/Unicode.php\n";
echo "  - Classic presenter: src/Output/Presenter/Classic.php\n";
echo "  - CI presenter: src/Output/Presenter/Ci.php\n";
echo "  - Documentation: doc/Horde/Cli/PRESENTATION.md\n";
echo str_repeat('=', 70) . "\n\n";
