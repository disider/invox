<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Model;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface, \Serializable
{
    public function getDecimalPoint();

    public function canManageMultipleCompanies();

    public function getEmail();

    public function getConfirmationToken();

    public function getResetPasswordToken();
}
