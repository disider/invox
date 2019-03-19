<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\App;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServiceTestCase extends WebTestCase
{
    public function setUp()
    {
        static::bootKernel([]);
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
