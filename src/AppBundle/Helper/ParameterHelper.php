<?php

namespace AppBundle\Helper;

class ParameterHelper implements ParameterHelperInterface
{
    private $enableDemoMode;

    public function __construct($enableDemoMode)
    {
        $this->enableDemoMode = $enableDemoMode;
    }

    public function isInDemoMode()
    {
        return $this->enableDemoMode;
    }
}