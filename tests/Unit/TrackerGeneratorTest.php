<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs\tests\Unit;

use Piwik\Plugins\CustomTrackerJs\Additions\Addition;
use Piwik\Plugins\CustomTrackerJs\TrackerGenerator;

/**
 * @group CustomTrackerJs
 * @group Plugins
 */
class TrackerGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $code = <<<JS
// This is some JS
JS;
        $expected = <<<JS

/* GENERATED: plugin additions */
// Foo top
/* END GENERATED: plugin additions */
// This is some JS
/* GENERATED: plugin additions */
// Foo bottom
/* END GENERATED: plugin additions */

JS;
        $generator = new TrackerGenerator();
        $this->assertEquals($expected, $generator->generate($code, new Addition('// Foo top', '// Foo bottom')));
    }

    public function testEmptyAdditionShouldDoNothing()
    {
        $code = <<<JS
// This is some JS
JS;
        $expected = <<<JS
// This is some JS
JS;

        $generator = new TrackerGenerator();
        $this->assertEquals($expected, $generator->generate($code, new Addition('', '')));
    }

    public function testOverwrite()
    {
        $code = <<<JS

/* GENERATED: plugin additions */
// Foo top
/* END GENERATED: plugin additions */
// This is some JS
/* GENERATED: plugin additions */
// Foo bottom
/* END GENERATED: plugin additions */

JS;
        $expected = <<<JS

/* GENERATED: plugin additions */
// Bar top
/* END GENERATED: plugin additions */
// This is some JS
/* GENERATED: plugin additions */
// Bar bottom
/* END GENERATED: plugin additions */

JS;

        $generator = new TrackerGenerator();
        $this->assertEquals($expected, $generator->generate($code, new Addition('// Bar top', '// Bar bottom')));
    }

    public function testHandleMultipleBlocks()
    {
        $code = <<<JS
// This is some JS

/* GENERATED: plugin additions */
// Foo
/* END GENERATED: plugin additions */


/* GENERATED: plugin additions */
// Bar
/* END GENERATED: plugin additions */

JS;
        $expected = <<<JS

/* GENERATED: plugin additions */
// Hello top
/* END GENERATED: plugin additions */
// This is some JS
/* GENERATED: plugin additions */
// Hello bottom
/* END GENERATED: plugin additions */

JS;
        $generator = new TrackerGenerator();
        $this->assertEquals($expected, $generator->generate($code, new Addition('// Hello top', '// Hello bottom')));
    }
}
