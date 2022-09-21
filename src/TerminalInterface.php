<?php
/**
 * Copyright 2003-2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @author   Chuck Hagenbuch <chuck@horde.org>
 * @author   Jan Schneider <jan@horde.org>
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL
 * @package  Cli
 */

namespace Horde\Cli;

use Horde_String;
use Horde\Support\Backtrace;
use Throwable;
use Exception;

/**
 * Terminal environment introspection
 *
 * @author    Chuck Hagenbuch <chuck@horde.org>
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2003-2017 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL
 * @package   Cli
 */
interface TerminalInterface
{
    /**
     * Detect from SAPI if running CLI
     */
    public static function runningFromCLI(): bool;
}