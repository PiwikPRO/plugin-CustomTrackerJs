<?php
/**
 * Copyright (C) Piwik PRO - All rights reserved.
 *
 * Using this code requires that you first get a license from Piwik PRO.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @link http://piwik.pro
 */

namespace Piwik\Plugins\CustomTrackerJs;

use Piwik\Plugins\CustomTrackerJs\Additions\Extension;
use Piwik\Plugins\CustomTrackerJs\Exception\AccessDeniedException;

class TrackingCodeFile
{
    /**
     * @var string
     */
    private $originalFile;

    /**
     * @var string
     */
    private $copyFile;

    /** @var Extension */
    private $extension;

    public function __construct($originalFile)
    {
        $this->originalFile = $originalFile;
        $this->copyFile = $originalFile . '._ct_bck';
    }

    public function addExtension(Extension $extension)
    {
        $this->extension = $extension;
    }

    public function save()
    {
        if (!$this->hasAccess()) {
            throw new AccessDeniedException("You have no access to piwik.js file");
        }

        if (!$this->hasCopy()) {
            $this->createCopyOfTrackerFile();
        }

        file_put_contents($this->originalFile, $this->getContentByExtension());
    }

    /**
     * @return bool
     */
    private function hasAccess()
    {
        return is_readable($this->originalFile) && is_writable($this->originalFile);
    }

    /**
     * @return string
     */
    private function getContentByExtension()
    {
        return $this->getSignatureWithContent($this->extension->getTopCode()) .
               file_get_contents($this->copyFile) .
               $this->getSignatureWithContent($this->extension->getBottomCode());
    }

    /**
     * @param string $content
     * @return string
     */
    private function getSignatureWithContent($content)
    {
        return sprintf(
            "\n/* GENERATED: plugin additions */\n%s\n/* END GENERATED: plugin additions */\n",
            $content
        );
    }

    private function createCopyOfTrackerFile()
    {
        file_put_contents(
            $this->copyFile,
            file_get_contents($this->originalFile)
        );
    }

    /**
     * @return bool
     */
    private function hasCopy()
    {
        return file_exists($this->copyFile);
    }
}
