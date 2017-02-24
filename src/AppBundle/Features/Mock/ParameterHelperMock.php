<?php


namespace AppBundle\Features\Mock;


use AppBundle\Helper\ParameterHelper;

class ParameterHelperMock extends ParameterHelper
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