<?php
/**
 * Copyright (C) Piwik PRO - All rights reserved.
 *
 * Using this code requires that you first get a license from Piwik PRO.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @link http://piwik.pro
 */

namespace Piwik\Plugins\CustomTrackerJs\Additions;

class Addition
{
    private $topCode;

    private $bottomCode;

    public function __construct($topCode, $bottomCode)
    {
        $this->topCode = $topCode;
        $this->bottomCode = $bottomCode;
    }

    /**
     * @return mixed
     */
    public function getBottomCode()
    {
        return $this->bottomCode;
    }

    /**
     * @param mixed $bottomCode
     */
    public function setBottomCode($bottomCode)
    {
        $this->bottomCode = $bottomCode;
    }

    /**
     * @return mixed
     */
    public function getTopCode()
    {
        return $this->topCode;
    }

    /**
     * @param mixed $topCode
     */
    public function setTopCode($topCode)
    {
        $this->topCode = $topCode;
    }


}
