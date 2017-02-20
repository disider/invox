<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServiceTestCase extends WebTestCase
{
    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected static $container;

    public static function setUpBeforeClass()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        static::$container = $kernel->getContainer();
    }

    protected static function getService($service)
    {
        return static::$container->get($service);
    }

    protected function assertService($id, $class)
    {
        $service = $this->getService($id);
        $this->assertInstanceOf($class, $service);
    }
}
