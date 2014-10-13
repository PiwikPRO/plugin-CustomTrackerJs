<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs;

/**
 * Generates the Javascript tracker.
 */
class TrackerGenerator
{
    /**
     * @param string $currentJS Current Javascript tracker code.
     * @param string $addition  Code to add to the tracker.
     *
     * @return string The new JS tracker code.
     */
    public function generate($currentJS, $addition)
    {
        $result = $this->removeExistingCodeAddition($currentJS);

        return $this->appendAddition($result, $addition);
    }

    private function removeExistingCodeAddition($result)
    {
        $pattern = '#\/\* GENERATED: plugin additions \*\/(.*)\/\* END GENERATED: plugin additions \*\/#sU';

        $result = preg_replace($pattern, '', $result);

        $result = rtrim($result);
        return $result;
    }

    private function appendAddition($code, $addition)
    {
        if ($addition == '') {
            return $code;
        }

        $code .= <<<STR


/* GENERATED: plugin additions */
$addition
/* END GENERATED: plugin additions */
STR;

        return $code;
    }
}
