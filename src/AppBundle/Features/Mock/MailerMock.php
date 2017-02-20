<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Features\Mock;

use AppBundle\Entity\Invite;
use AppBundle\Entity\User;
use AppBundle\Mailer\Mailer;

class MailerMock implements Mailer
{
    private $template;
    private $to;
    private $from;

    public function sendConfirmRegistrationEmailTo(User $user)
    {
        $this->registerMail('registration_confirm', $user->getEmail());
    }

    public function sendRegistrationCompletedEmailTo(User $user)
    {
        $this->registerMail('registration_completed', $user->getEmail());
    }

    public function sendResetPasswordRequestEmailTo(User $user)
    {
        $this->registerMail('request_reset_password', $user->getEmail());
    }

    public function sendInviteAccountantEmailTo(Invite $invite)
    {
        $this->registerMail('invite_accountant', $invite->getEmail());
    }

    public function sendContactUsMail($email, $subject, $body)
    {
        $this->registerMail('contact_us', null, $email);
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getFrom()
    {
        return $this->from;
    }

    protected function registerMail($template, $toEmail, $fromEmail = null)
    {
        $this->template = $template;
        $this->to = $toEmail;
        $this->from = $fromEmail;
    }
}
