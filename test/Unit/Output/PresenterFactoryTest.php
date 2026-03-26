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

use PHPUnit\Framework\Attributes\CoversNothing;
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
 */
#[CoversNothing]
class PresenterFactoryTest extends TestCase
{
    private $cli;
    private array $originalEnv = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->cli = $this->createMock(Horde_Cli::class);

        // Save original environment
        $this->originalEnv = [
            'LANG' => getenv('LANG'),
            'LC_ALL' => getenv('LC_ALL'),
            'TERM' => getenv('TERM'),
            'SSH_CONNECTION' => getenv('SSH_CONNECTION'),
            'SSH_CLIENT' => getenv('SSH_CLIENT'),
            'GITHUB_ACTIONS' => getenv('GITHUB_ACTIONS'),
            'GITLAB_CI' => getenv('GITLAB_CI'),
            'JENKINS_HOME' => getenv('JENKINS_HOME'),
            'CIRCLECI' => getenv('CIRCLECI'),
            'TRAVIS' => getenv('TRAVIS'),
            'CI' => getenv('CI'),
        ];
    }

    protected function tearDown(): void
    {
        // Restore original environment
        foreach ($this->originalEnv as $key => $value) {
            if ($value === false) {
                putenv($key);  // Unset
            } else {
                putenv("$key=$value");
            }
        }
        parent::tearDown();
    }

    /**
     * Set up a clean environment for testing autodetect
     */
    private function setCleanEnvironment(): void
    {
        // Clear all CI-related env vars
        putenv('GITHUB_ACTIONS');
        putenv('GITLAB_CI');
        putenv('JENKINS_HOME');
        putenv('CIRCLECI');
        putenv('TRAVIS');
        putenv('CI');

        // Clear SSH vars
        putenv('SSH_CONNECTION');
        putenv('SSH_CLIENT');
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

    public function testAutodetectDetectsCiEnvironment(): void
    {
        // Set up CI environment
        $this->setCleanEnvironment();
        putenv('GITHUB_ACTIONS=true');

        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'autodetect']);
        $this->assertInstanceOf(Ci::class, $presenter, 'Autodetect should return CI presenter in GitHub Actions environment');
    }

    public function testAutodetectDetectsGitLabCi(): void
    {
        $this->setCleanEnvironment();
        putenv('GITLAB_CI=true');

        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'autodetect']);
        $this->assertInstanceOf(Ci::class, $presenter, 'Autodetect should return CI presenter in GitLab CI environment');
    }

    public function testAutodetectDetectsUnicodeInModernTerminal(): void
    {
        // Set up modern terminal with UTF-8
        $this->setCleanEnvironment();
        putenv('LANG=en_US.UTF-8');
        putenv('TERM=xterm-256color');

        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'autodetect']);
        $this->assertInstanceOf(Unicode::class, $presenter, 'Autodetect should return Unicode presenter in modern terminal with UTF-8');
    }

    public function testAutodetectFallsBackToClassicWithoutUtf8(): void
    {
        // Set up terminal without UTF-8
        $this->setCleanEnvironment();
        putenv('LANG=C');  // No UTF-8
        putenv('TERM=xterm');

        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'autodetect']);
        $this->assertInstanceOf(Classic::class, $presenter, 'Autodetect should fall back to Classic without UTF-8 support');
    }

    public function testAutodetectFallsBackToClassicInOldTerminal(): void
    {
        // Set up old terminal that doesn't match any pattern
        $this->setCleanEnvironment();
        putenv('LANG=en_US.UTF-8');
        putenv('TERM=dumb');  // 'dumb' terminal doesn't match any pattern

        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'autodetect']);
        $this->assertInstanceOf(Classic::class, $presenter, 'Autodetect should fall back to Classic in dumb terminal');
    }

    public function testAutodetectAvoidsUnicodeOverSsh(): void
    {
        // Set up SSH connection (even with UTF-8 and modern terminal)
        $this->setCleanEnvironment();
        putenv('LANG=en_US.UTF-8');
        putenv('TERM=xterm-256color');
        putenv('SSH_CONNECTION=192.168.1.1 12345 192.168.1.2 22');

        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'autodetect']);
        $this->assertInstanceOf(Classic::class, $presenter, 'Autodetect should avoid Unicode over SSH connection');
    }

    public function testNoOptionsDefaultsToAutodetect(): void
    {
        // No options should trigger autodetect
        // Set up a predictable environment to test this
        $this->setCleanEnvironment();
        putenv('LANG=en_US.UTF-8');
        putenv('TERM=xterm-256color');

        $presenter = PresenterFactory::create($this->cli);
        $this->assertInstanceOf(Unicode::class, $presenter, 'No options should trigger autodetect (Unicode in this test environment)');
    }

    public function testUnknownFormatDefaultsToClassic(): void
    {
        $presenter = PresenterFactory::create($this->cli, ['cli_format' => 'unknown']);
        $this->assertInstanceOf(Classic::class, $presenter, 'Unknown format should fall back to Classic');
    }
}
