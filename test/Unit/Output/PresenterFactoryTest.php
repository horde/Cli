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

namespace Horde\Cli\Test\Unit\Output;

use PHPUnit\Framework\TestCase;
use Horde\Cli\Output\PresenterFactory;
use Horde\Cli\Output\Presenter\Unicode;
use Horde\Cli\Output\Presenter\Classic;
use Horde\Cli\Output\Presenter\Ci;
use Horde_Cli;

/**
 * Test the PresenterFactory.
 *
 * @category Horde
 * @package  Cli
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @coversNothing
 */
class PresenterFactoryTest extends TestCase
{
    private $cli;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cli = $this->createMock(Horde_Cli::class);
    }

    public function testCreateWithExplicitClassic(): void
    {
        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'classic']);
        $this->assertInstanceOf(Classic::class, $presenter);
    }

    public function testCreateWithExplicitUnicode(): void
    {
        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'unicode']);
        $this->assertInstanceOf(Unicode::class, $presenter);
    }

    public function testCreateWithExplicitCi(): void
    {
        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'ci']);
        $this->assertInstanceOf(Ci::class, $presenter);
    }

    public function testCreateWithAutodetectDefaultsToClassic(): void
    {
        // Without special environment variables, should default to classic
        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'autodetect']);
        $this->assertInstanceOf(Classic::class, $presenter);
    }

    public function testCreateWithNoOptionsDefaultsToAutodetect(): void
    {
        // No options should trigger autodetect
        $presenter = PresenterFactory::create($this->cli);
        $this->assertInstanceOf(Classic::class, $presenter);
    }

    public function testCreateWithUnknownFormatDefaultsToClassic(): void
    {
        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'unknown']);
        $this->assertInstanceOf(Classic::class, $presenter);
    }
}
