# Piwik CustomTrackerJs Plugin

[![Build Status](https://travis-ci.org/PiwikPRO/plugin-CustomTrackerJs.svg?branch=master)](https://travis-ci.org/PiwikPRO/plugin-CustomTrackerJs)

## Description

The `CustomTrackerJs` plugin lets the super users or other plugins add custom code to the Piwik Javascript Tracker.

## Usage

### Super users

Super users can set the Javascript code they want to add to the `piwik.js` file in `Settings > Plugin settings`.

Any code added in the *Javascript code* textbox will be appended to `piwik.js` by a scheduled task. Be very careful
as to write valid Javascript code since invalid code can break the tracker.

### Other plugins

Plugins can make use of the `CustomTrackerJs.getTrackerJsAdditions` event to register Javascript code to add to
the tracker.

For example this will add a `console.log("Hello world!");` line to the tracker:

```php
class MyPlugin extends \Piwik\Plugin
{
    public function getListHooksRegistered()
    {
        return array(
            'CustomTrackerJs.getTrackerJsAdditions' => 'getTrackerJsAdditions',
        );
    }

    public function getTrackerJsAdditions(&$code)
    {
        $code .= PHP_EOL . 'console.log("Hello world!");';
    }
}
```

## Changelog

* 1.1.2
    - Marketplace release
* 1.1.0
    - PPCDEV-2609 Compatibility with Piwik 2.16.0

## Support

Please contact us at contact@piwik.pro in case you are facing a bug.


Plugin created and maintained by [Piwik PRO](http://piwik.pro/).
