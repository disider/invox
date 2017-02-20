<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Mailer;

use AppBundle\Entity\Invite;
use AppBundle\Entity\User;

interface Mailer
{
    public function sendConfirmRegistrationEmailTo(User $user);

    public function sendRegistrationCompletedEmailTo(User $user);

    public function sendResetPasswordRequestEmailTo(User $user);

    public function sendInviteAccountantEmailTo(Invite $invite);

    public function sendContactUsMail($email, $subject, $body);
}
