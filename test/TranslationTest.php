<?php

/**
 * Copyright 2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Cli
 */

declare(strict_types=1);

namespace Horde\Cli\Test;

use Horde\Cli\Translation;
use Horde\Translation\GettextHandler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Translation class
 *
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package  Cli
 */
#[CoversClass(Translation::class)]
class TranslationTest extends TestCase
{
    public function testTranslationCanLoad(): void
    {
        // Should not throw exception about missing locale directory
        Translation::loadHandler(GettextHandler::class);

        // Translation loading should succeed
        $this->assertTrue(true);
    }

    public function testLocaleDirectoryExists(): void
    {
        // Verify the locale directory exists in the package
        $localeDir = dirname(__DIR__) . '/locale';
        $this->assertDirectoryExists($localeDir);
    }
}
