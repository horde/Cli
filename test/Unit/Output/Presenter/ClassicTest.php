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

use PHPUnit\Framework\TestCase;
use Horde\Cli\Output\Presenter\Classic;
use Horde_Cli;

/**
 * Test the Classic presenter.
 *
 * @category Horde
 * @package  Cli
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @coversNothing
 */
class ClassicTest extends TestCase
{
    private $cli;
    private $output = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock Horde_Cli that captures output
        $this->cli = $this->createMock(Horde_Cli::class);
        $this->output = [];

        // Capture message() calls
        $this->cli->method('message')
            ->willReturnCallback(function ($text, $type) {
                $this->output[] = ['method' => 'message', 'text' => $text, 'type' => $type];
            });

        // Capture writeln() calls
        $this->cli->method('writeln')
            ->willReturnCallback(function ($text) {
                $this->output[] = ['method' => 'writeln', 'text' => $text];
            });

        // Mock formatting methods
        $this->cli->method('bold')
            ->willReturnCallback(fn($text) => "**{$text}**");
    }

    public function testOkCallsMessageWithSuccessType(): void
    {
        $presenter = new Classic($this->cli, false);
        $presenter->ok('Test success message');

        $this->assertCount(1, $this->output);
        $this->assertEquals('message', $this->output[0]['method']);
        $this->assertEquals('Test success message', $this->output[0]['text']);
        $this->assertEquals('cli.success', $this->output[0]['type']);
    }

    public function testWarnCallsMessageWithWarningType(): void
    {
        $presenter = new Classic($this->cli, false);
        $presenter->warn('Test warning message');

        $this->assertCount(1, $this->output);
        $this->assertEquals('cli.warning', $this->output[0]['type']);
    }

    public function testInfoCallsMessageWithMessageType(): void
    {
        $presenter = new Classic($this->cli, false);
        $presenter->info('Test info message');

        $this->assertCount(1, $this->output);
        $this->assertEquals('cli.message', $this->output[0]['type']);
    }

    public function testErrorCallsMessageWithErrorType(): void
    {
        $presenter = new Classic($this->cli, false);
        $presenter->error('Test error message');

        $this->assertCount(1, $this->output);
        $this->assertEquals('cli.error', $this->output[0]['type']);
    }

    public function testNoColorDisablesTypeFormatting(): void
    {
        $presenter = new Classic($this->cli, true);  // nocolor = true
        $presenter->ok('Test message');

        $this->assertCount(1, $this->output);
        $this->assertEquals('', $this->output[0]['type']);  // Empty type when nocolor
    }

    public function testPlainCallsWriteln(): void
    {
        $presenter = new Classic($this->cli, false);
        $presenter->plain('Plain text');

        $this->assertCount(1, $this->output);
        $this->assertEquals('writeln', $this->output[0]['method']);
        $this->assertEquals('Plain text', $this->output[0]['text']);
    }
}
