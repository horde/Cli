<?php
/**
 * Copyright 2003-2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @author   Ralf Lang <ralf.lang@ralf-lang.de>
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
 * Minimalistic wrapper of input stream resources
 *
 * This is not a fancy API.
 * 
 * It is mostly for type-safe passing around,
 * allowing for injection-friendly interfaces and unwrapping the actual resource for consumption
 * 
 * Think about moving this somewhere but don't put it into a dependency-heavy environment
 * 
 * @author    Ralf Lang <ralf.lang@ralf-lang.de>
 * @category  Horde
 * @copyright 2003-2022 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL
 * @package   Cli
 */
class InputStreamWrapper extends StreamResourceWrapper
{
    private $resource;
    /**
     * Detect from SAPI if running CLI
     */
    public function __construct($resource = null)
    {
        if ($resource === null) {
            $resource = fopen('php://stdin', 'r');
        }
        parent::__construct($resource);
    }
}