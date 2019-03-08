<?php

namespace Tests\App\Fake;

use App\Helper\ParameterHelperInterface;

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