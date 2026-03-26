<?php

/**
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

declare(strict_types=1);

namespace Horde\Cli\Test\Unit\Output\Presenter;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Horde\Cli\Output\Presenter\Unicode;
use Horde_Cli;

/**
 * Test the Unicode presenter.
 *
 * @category Horde
 * @package  Cli
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 */
#[CoversNothing]
class UnicodeTest extends TestCase
{
    private $cli;
    private $output = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock Horde_Cli that captures output
        $this->cli = $this->createMock(Horde_Cli::class);
        $this->output = [];

        // Capture writeln() calls
        $this->cli->method('writeln')
            ->willReturnCallback(function ($text) {
                $this->output[] = $text;
            });

        // Mock color formatting methods
        $this->cli->method('bold')
            ->willReturnCallback(fn($text) => "**{$text}**");
        $this->cli->method('blue')
            ->willReturnCallback(fn($text) => "BLUE[{$text}]");
        $this->cli->method('green')
            ->willReturnCallback(fn($text) => "GREEN[{$text}]");
        $this->cli->method('yellow')
            ->willReturnCallback(fn($text) => "YELLOW[{$text}]");
        $this->cli->method('red')
            ->willReturnCallback(fn($text) => "RED[{$text}]");
    }

    public function testOkWithColors(): void
    {
        $presenter = new Unicode($this->cli, true);
        $presenter->ok('Success message');

        $this->assertCount(1, $this->output);
        $this->assertStringContainsString('✅', $this->output[0]);
        $this->assertStringContainsString('Success message', $this->output[0]);
        $this->assertStringContainsString('GREEN', $this->output[0]);
    }

    public function testOkWithoutColors(): void
    {
        $presenter = new Unicode($this->cli, false);
        $presenter->ok('Success message');

        $this->assertCount(1, $this->output);
        $this->assertStringContainsString('✅', $this->output[0]);
        $this->assertStringContainsString('Success message', $this->output[0]);
        $this->assertStringNotContainsString('GREEN', $this->output[0]);
    }

    public function testWarnWithColors(): void
    {
        $presenter = new Unicode($this->cli, true);
        $presenter->warn('Warning message');

        $this->assertCount(1, $this->output);
        $this->assertStringContainsString('⚠️', $this->output[0]);
        $this->assertStringContainsString('Warning message', $this->output[0]);
        $this->assertStringContainsString('YELLOW', $this->output[0]);
    }

    public function testInfoWithColors(): void
    {
        $presenter = new Unicode($this->cli, true);
        $presenter->info('Info message');

        $this->assertCount(1, $this->output);
        $this->assertStringContainsString('ℹ️', $this->output[0]);
        $this->assertStringContainsString('Info message', $this->output[0]);
        $this->assertStringContainsString('BLUE', $this->output[0]);
    }

    public function testErrorWithColors(): void
    {
        $presenter = new Unicode($this->cli, true);
        $presenter->error('Error message');

        $this->assertCount(1, $this->output);
        $this->assertStringContainsString('❌', $this->output[0]);
        $this->assertStringContainsString('Error message', $this->output[0]);
        $this->assertStringContainsString('RED', $this->output[0]);
    }

    public function testSemanticDetected(): void
    {
        $presenter = new Unicode($this->cli, false);
        $presenter->semantic('detected', 'Found tool');

        $this->assertCount(1, $this->output);
        $this->assertStringContainsString('🔍', $this->output[0]);
        $this->assertStringContainsString('Found tool', $this->output[0]);
    }

    public function testSemanticCreated(): void
    {
        $presenter = new Unicode($this->cli, true);
        $presenter->semantic('created', 'File created');

        $this->assertStringContainsString('📝', $this->output[0]);
        $this->assertStringContainsString('GREEN', $this->output[0]);
    }

    public function testSemanticValidation(): void
    {
        $presenter = new Unicode($this->cli, true);
        $presenter->semantic('validation', 'Validation failed');

        $this->assertStringContainsString('✗', $this->output[0]);
        $this->assertStringContainsString('RED', $this->output[0]);
    }

    public function testSemanticAcceptsTraditionalLevels(): void
    {
        $presenter = new Unicode($this->cli, true);

        $presenter->semantic('ok', 'Success');
        $presenter->semantic('warn', 'Warning');
        $presenter->semantic('info', 'Info');
        $presenter->semantic('error', 'Error');

        $this->assertCount(4, $this->output);
        $this->assertStringContainsString('✅', $this->output[0]);
        $this->assertStringContainsString('⚠️', $this->output[1]);
        $this->assertStringContainsString('ℹ️', $this->output[2]);
        $this->assertStringContainsString('❌', $this->output[3]);
    }
}
