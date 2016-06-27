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

class ExtensionFactory
{
    /**
     * @return Extension
     */
    public static function createFromEvent()
    {
        $extension = new Extension();

        Piwik::postEvent('CustomTrackerJs.getTrackerJsExtension', [$extension]);

        $bottomCode = '';
        /** @deprecated */
        Piwik::postEvent('CustomTrackerJs.getTrackerJsAdditions', [&$bottomCode]);

        if ($bottomCode != '') {
            $extension->setBottomCode($bottomCode);
        }

        return $extension;
    }
}
