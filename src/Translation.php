<?php

/**
 * Copyright 2010-2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @author   Jan Schneider <jan@horde.org>
 * @category Horde
 * @license  http://www.horde.org/licenses/lgpl21 LGPL
 * @package  Cli
 */

namespace Horde\Cli;

use Horde\Translation\Autodetect;

/**
 * Horde_Cli_Translation is the translation wrapper class for Horde_Cli.
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2010-2017 Horde LLC
 * @license   http://www.horde.org/licenses/lgpl21 LGPL
 * @package   Cli
 */
class Translation extends Autodetect
{
    /**
     * The translation domain
     *
     * @var string
     */
    protected static string $domain = 'Horde_Cli';

    /**
     * The absolute PEAR path to the translations for the default gettext handler.
     *
     * @var string
     */
    protected static string $pearDirectory = '@data_dir@';
}
