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
use Horde\Cli\Output\Presenter\Ci;

/**
 * Test the CI presenter.
 *
 * @category Horde
 * @package  Cli
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 */
#[CoversNothing]
class CiTest extends TestCase
{
    private $output;
    private $stream;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a memory stream to capture output
        $this->stream = fopen('php://memory', 'r+');
        $this->output = '';
    }

    protected function tearDown(): void
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
        parent::tearDown();
    }

    private function getOutput(): string
    {
        rewind($this->stream);
        return stream_get_contents($this->stream);
    }

    public function testOkOutputsPlainFormat(): void
    {
        $presenter = new Ci($this->stream);
        $presenter->ok('Success message');

        $output = $this->getOutput();
        $this->assertStringContainsString('[OK]', $output);
        $this->assertStringContainsString('Success message', $output);
    }

    public function testWarnOutputsPlainFormat(): void
    {
        $presenter = new Ci($this->stream);
        $presenter->warn('Warning message');

        $output = $this->getOutput();
        $this->assertStringContainsString('[WARN]', $output);
    }

    public function testInfoOutputsPlainFormat(): void
    {
        $presenter = new Ci($this->stream);
        $presenter->info('Info message');

        $output = $this->getOutput();
        $this->assertStringContainsString('[INFO]', $output);
    }

    public function testErrorOutputsPlainFormat(): void
    {
        $presenter = new Ci($this->stream);
        $presenter->error('Error message');

        $output = $this->getOutput();
        $this->assertStringContainsString('[ERROR]', $output);
    }

    public function testSemanticDetected(): void
    {
        $presenter = new Ci($this->stream);
        $presenter->semantic('detected', 'Found tool');

        $output = $this->getOutput();
        $this->assertStringContainsString('[DETECT]', $output);
        $this->assertStringContainsString('Found tool', $output);
    }

    public function testSemanticCreated(): void
    {
        $presenter = new Ci($this->stream);
        $presenter->semantic('created', 'File created');

        $output = $this->getOutput();
        $this->assertStringContainsString('[CREATE]', $output);
    }

    public function testPlainOutputsWithoutLabel(): void
    {
        $presenter = new Ci($this->stream);
        $presenter->plain('Plain text');

        $output = $this->getOutput();
        $this->assertEquals("Plain text\n", $output);
    }
}
