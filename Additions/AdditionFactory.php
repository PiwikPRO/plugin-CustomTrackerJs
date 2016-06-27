<?php
/**
 * Copyright (C) Piwik PRO - All rights reserved.
 *
 * Using this code requires that you first get a license from Piwik PRO.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @link http://piwik.pro
 */

namespace Piwik\Plugins\CustomTrackerJs\Additions;

use Piwik\Piwik;

class AdditionFactory
{
    public static function createFromEvent()
    {
        $topCode = '';
        $bottomCode = '';

        Piwik::postEvent('CustomTrackerJs.getTrackerJsAdditionsTop', [&$topCode]);
        Piwik::postEvent('CustomTrackerJs.getTrackerJsAdditionsBottom', [&$bottomCode]);

        return new Addition($topCode, $bottomCode);
    }
}
