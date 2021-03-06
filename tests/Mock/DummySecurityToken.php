<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\App\Mock;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class DummySecurityToken extends AbstractToken
{
    public function __construct($user = null)
    {
        if ($user != null) {
            $this->setUser($user);
        }
    }

    public function getCredentials()
    {
    }
}
