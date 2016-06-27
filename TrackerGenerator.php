<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs;

use Piwik\Plugins\CustomTrackerJs\Additions\Addition;

/**
 * Generates the Javascript tracker.
 */
class TrackerGenerator
{
    /**
     * @param string $currentJS Current Javascript tracker code.
     * @param Addition $addition  Code to add to the tracker.
     *
     * @return string The new JS tracker code.
     */
    public function generate($currentJS, Addition $addition)
    {
        $result = $this->removeExistingCodeAddition($currentJS);

        return $this->appendAddition($result, $addition);
    }

    private function removeExistingCodeAddition($result)
    {
        $pattern = '#\n\/\* GENERATED: plugin additions \*\/\n(.*)\n\/\* END GENERATED: plugin additions \*\/\n#sU';

        $result = preg_replace($pattern, '', $result);

        $result = rtrim($result);
        return $result;
    }

    private function appendAddition($code, Addition $addition)
    {
        return $this->getSignature($addition->getTopCode()) .
               $code .
               $this->getSignature($addition->getBottomCode());
    }

    private function getSignature($content)
    {
        if ($content === '') {
            return $content;
        }

        return sprintf("\n/* GENERATED: plugin additions */\n%s\n/* END GENERATED: plugin additions */\n", $content);
    }
}
