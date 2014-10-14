<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs;

use Piwik\Piwik;

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
        if (! (file_exists($this->file) && is_readable($this->file) && is_writable($this->file))) {
            throw new \InvalidArgumentException("The file '$this->file' doesn't exist or is not writable");
        }

        $originalJs = file_get_contents($this->file);
        $addition = $this->getCustomJsAdditions();

        $generator = new TrackerGenerator();
        $js = $generator->generate($originalJs, $addition);

        if ($originalJs !== $js) {
            file_put_contents($this->file, $js);
        }
    }

    private function getCustomJsAdditions()
    {
        $code = '';

        /**
         * This event lets plugins add custom Javascript to the Tracker.
         *
         * @param string $code The Javascript code to add to the original tracker.
         */
        Piwik::postEvent('CustomTrackerJs.getTrackerJsAdditions', array(&$code));

        return $code;
    }
}
