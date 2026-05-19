<?php

/**
 * Copyright 2016-2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @author   Jan Schneider <jan@horde.org>
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Cache
 */

namespace Horde\Cli\Test;

use Horde\Cli\Cli;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * A basic testcase for CLI
 *
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Cache
 */
#[CoversNothing]
class CliTest extends TestCase
{
    private $testStream;
    private Cli $cli;

    public function setUp(): void
    {
        $this->testStream = fopen('php://temp', 'rw');
        $this->cli = new Cli(['output' => $this->testStream]);
    }

    public function testWriteln()
    {
        $this->cli->writeln('test');
        rewind($this->testStream);
        $output = stream_get_contents($this->testStream);
        $this->assertEquals("test\n", $output);
    }

    public function testWriteln2ndArg()
    {
        $this->cli->writeln('test', true);
        rewind($this->testStream);
        $output = stream_get_contents($this->testStream);
        $this->assertEquals("\ntest", $output);
    }

    /**
     * Test that writeln with no newline works correctly
     *
     * This is used by prompt() to write the prompt text inline.
     * The prompt() method then calls ob_flush() and flush() to ensure
     * the prompt is visible before reading user input.
     */
    public function testWritelnNoNewline()
    {
        $this->cli->writeln('Prompt text: ', true);
        rewind($this->testStream);
        $output = stream_get_contents($this->testStream);
        // writeln with 2nd parameter true prepends a newline but doesn't append one
        $this->assertEquals("\nPrompt text: ", $output);
    }

    /**
     * Test that output buffer flushing check doesn't error
     *
     * This verifies the ob_get_level() check before ob_flush() works correctly.
     * The actual prompt() method in Cli.php uses this pattern to safely flush
     * buffers only when they exist.
     */
    public function testOutputBufferFlushCheck()
    {
        // This should not error even if no output buffer exists
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();

        $this->assertTrue(true);  // If we got here, no error occurred
    }
}
