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

class ExtensionCollection
{
    /**
     * @var Extension[]
     */
    private $extensions;

    public function __construct()
    {
        $this->extensions = [];
    }

    public function add(Extension $extension)
    {
        $this->extensions[] = $extension;
    }

    /**
     * @param string $name
     */
    public function removeByName($name)
    {
        $this->extensions = array_filter(
            $this->extensions,
            function (Extension $extension) use ($name) {
                return $extension->getName() != $name;
            }
        );
    }

    /**
     * @return Extension[]
     */
    public function asArray()
    {
        return $this->extensions;
    }
}
