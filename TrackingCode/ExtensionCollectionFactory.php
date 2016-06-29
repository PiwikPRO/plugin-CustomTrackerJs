<?php
/**
 * Copyright (C) Piwik PRO - All rights reserved.
 *
 * Using this code requires that you first get a license from Piwik PRO.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @link http://piwik.pro
 */

namespace Piwik\Plugins\CustomTrackerJs\TrackingCode;

use Piwik\Piwik;

class ExtensionCollectionFactory
{
    /**
     * @return ExtensionCollection
     */
    public static function createFromEvent()
    {
        $extensionCollection = new ExtensionCollection();

        Piwik::postEvent('CustomTrackerJs.getTrackerJsExtension', [$extensionCollection]);

        $bottomCode = '';
        /** @deprecated */
        Piwik::postEvent('CustomTrackerJs.getTrackerJsAdditions', [&$bottomCode]);

        if ($bottomCode != '') {
            $extension = new Extension('plugin additions');
            $extension->setCode($bottomCode);

            $extensionCollection->add($extension);
        }

        return $extensionCollection;
    }
}
