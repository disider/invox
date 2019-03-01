<?php

namespace AppBundle\Features\Fake;

use AppBundle\Helper\ParameterHelperInterface;

class FakeParameterHelper implements ParameterHelperInterface
{
    private $isInDemoMode = false;

    public function setDemoMode($isInDemoMode)
    {
        $this->isInDemoMode = $isInDemoMode;
    }

    public function isInDemoMode()
    {
        return $this->isInDemoMode;
    }
}