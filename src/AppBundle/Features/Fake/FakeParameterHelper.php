<?php

namespace AppBundle\Features\Fake;

use AppBundle\Helper\ParameterHelperInterface;

class FakeParameterHelper implements ParameterHelperInterface
{
    private static $isInDemoMode = false;

    public function setDemoMode($isInDemoMode)
    {
        self::$isInDemoMode = $isInDemoMode;
    }

    public function isInDemoMode()
    {
        return self::$isInDemoMode;
    }
}