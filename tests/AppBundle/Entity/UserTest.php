<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Tests\AppBundle\EntityTest;

class UserTest extends EntityTest
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $user = User::create('user@example.com', '', '');
        
        $this->assertThat($user->getUsername(), $this->equalTo('user@example.com'));
    }
}