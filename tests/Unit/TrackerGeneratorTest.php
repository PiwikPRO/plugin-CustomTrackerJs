<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs\tests\Unit;

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
// This is some JS

/* GENERATED: plugin additions */
// Foo
/* END GENERATED: plugin additions */
JS;

        $generator = new TrackerGenerator();
        $this->assertEquals($expected, $generator->generate($code, '// Foo'));
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
        $this->assertEquals($expected, $generator->generate($code, ''));
    }

    public function testOverwrite()
    {
        $code = <<<JS
// This is some JS

/* GENERATED: plugin additions */
// Foo
/* END GENERATED: plugin additions */
JS;
        $expected = <<<JS
// This is some JS

/* GENERATED: plugin additions */
// Bar
/* END GENERATED: plugin additions */
JS;

        $generator = new TrackerGenerator();
        $this->assertEquals($expected, $generator->generate($code, '// Bar'));
    }

    public function testHandleMultipleBlocks()
    {
        $code = <<<JS
// This is some JS
/* GENERATED: plugin additions */
// Foo
/* END GENERATED: plugin additions */
// This line should not be removed

/* GENERATED: plugin additions */
// Bar
/* END GENERATED: plugin additions */
JS;
        $expected = <<<JS
// This is some JS

// This line should not be removed

/* GENERATED: plugin additions */
// Hello
/* END GENERATED: plugin additions */
JS;

        $generator = new TrackerGenerator();
        $this->assertEquals($expected, $generator->generate($code, '// Hello'));
    }
}
