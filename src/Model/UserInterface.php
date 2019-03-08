<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Model;

interface UserInterface extends \Serializable
{
    public function getDecimalPoint();

    public function canManageMultipleCompanies();

    public function getEmail();

    public function getConfirmationToken();

    public function getResetPasswordToken();

    public function getPlainPassword();

    public function setPassword($password);
}
