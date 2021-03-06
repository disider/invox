<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Validator\Constraints;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class CurrentUserPassword extends UserPassword
{
    public $message = 'error.wrong_current_password';
}
