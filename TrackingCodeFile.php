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

use Piwik\Plugins\CustomTrackerJs\Exception\AccessDeniedException;
use Piwik\Plugins\CustomTrackerJs\TrackingCode\Extension;
use Piwik\Plugins\CustomTrackerJs\TrackingCode\ExtensionCollection;

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

    /** @var ExtensionCollection */
    private $extensionCollection;

    public function __construct($originalFile, ExtensionCollection $extensionCollection)
    {
        $this->originalFile = $originalFile;
        $this->copyFile = $originalFile . '._ct_bck';
        $this->extensionCollection = $extensionCollection;
    }

    public function save()
    {
        if (!$this->hasAccess()) {
            throw new AccessDeniedException("You have no access to piwik.js file");
        }

        if (!$this->hasCopy()) {
            $this->createCopyOfTrackerFile();
        }

        file_put_contents($this->originalFile, $this->getContent());
    }

    private function getContent()
    {
        $contentTop = '';
        $contentBottom = '';

        foreach ($this->extensionCollection->asArray() as $extension) {

            $content = $this->getSignatureWithContent($extension->getName(), $extension->getCode()) . PHP_EOL;

            switch ($extension->getPosition()) {
                case Extension::POSITION_TOP:
                    $contentTop .= $content;
                    break;
                case Extension::POSITION_BOTTOM:
                    $contentBottom .= $content;
                    break;

            }
        }

        return $contentTop . file_get_contents($this->copyFile) . $contentBottom;
    }

    /**
     * @return bool
     */
    private function hasAccess()
    {
        return is_readable($this->originalFile) && is_writable($this->originalFile);
    }

    /**
     * @param string $name
     * @param string $content
     * @return string
     */
    private function getSignatureWithContent($name, $content)
    {
        return sprintf(
            "\n/* GENERATED: %s */\n%s\n/* END GENERATED: %s */\n",
            $name,
            $content,
            $name
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
