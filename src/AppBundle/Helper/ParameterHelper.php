<?php

namespace AppBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ParameterHelper implements ParameterHelperInterface
{
    /** @var ContainerInterface */
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function isInDemoMode()
    {
        return $this->container->getParameter('enable_demo_mode');
    }
}