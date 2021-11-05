<?php
/**
 * Copyright 2016-2021 Horde LLC (http://www.horde.org/)
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
use PHPUnit\Framework\TestCase;

/**
 * A basic testcase for CLI
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Cache
 */
class CliTest extends TestCase
{
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
}
