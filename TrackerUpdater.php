<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs;

use Piwik\Plugins\CustomTrackerJs\TrackingCode\ExtensionCollectionFactory;

/**
 * Updates the Javascript file containing the Tracker.
 */
class TrackerUpdater
{
    const TRACKER_FILE = '/piwik.js';

    private $file;

    /**
     * @param string|null $file If null then the minified JS tracker will be updated.
     */
    public function __construct($file = null)
    {
        $this->file = $file ?: PIWIK_DOCUMENT_ROOT . self::TRACKER_FILE;
    }

    public function __invoke()
    {
        $trackingCode = new TrackingCodeFile($this->file, ExtensionCollectionFactory::createFromEvent());
        $trackingCode->save();
    }
}
