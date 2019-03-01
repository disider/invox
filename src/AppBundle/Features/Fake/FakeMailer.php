<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Features\Fake;

use AppBundle\Entity\Invite;
use AppBundle\Entity\User;
use AppBundle\Mailer\MailerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class Email
{
    public $sender;
    public $subject;
    public $body;
    public $recipients = [];

    public function hasRecipient($recipient)
    {
        foreach ($this->recipients as $r) {
            if ($r === $recipient) {
                return true;
            }
        }

        return false;
    }
}

class FakeMailer implements MailerInterface
{
    private $emails = [];
    private $serializer;
    private $fileName;

    public function __construct()
    {
        $this->fileName = '/tmp/emails.json';

        $encoders = [new JsonEncoder()];
        $normalizers = [new PropertyNormalizer(), new ArrayDenormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);

        $this->loadEmails();
    }

    public function sendConfirmRegistrationEmailTo(User $user)
    {
        $this->registerMail('registration_confirm', '', $user->getEmail());
    }

    public function sendRegistrationCompletedEmailTo(User $user)
    {
        $this->registerMail('registration_completed', '', $user->getEmail());
    }

    public function sendResetPasswordRequestEmailTo(User $user)
    {
        $this->registerMail('request_reset_password', '', $user->getEmail());
    }

    public function sendInviteAccountantEmailTo(Invite $invite)
    {
        $this->registerMail('invite_accountant', '', $invite->getEmail());
    }

    public function sendContactUsMail($email, $subject, $body)
    {
        $this->registerMail('contact_us', '', '', $email);
    }

    public function hasSubject($subject)
    {
        $this->loadEmails();

        foreach ($this->emails as $email) {
            if ($email->subject == $subject) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Email
     * @throws \Exception
     */
    public function getSubject($subject)
    {
        foreach ($this->emails as $email) {
            if ($email->subject == $subject) {
                return $email;
            }
        }

        throw new \Exception('Undefined subject: ' . $subject);
    }

    public function clearEmails()
    {
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }
    }

    protected function registerMail($subject, $body, $toEmail, $fromEmail = null)
    {
        $email = new Email();
        $email->subject = $subject;
        $email->body = $body;
        $email->sender = $fromEmail;
        $email->recipients[] = $toEmail;

        $this->emails[] = $email;

        $content = $this->serializer->serialize($this->emails, 'json', ['skip_null_values' => true]);
        file_put_contents($this->fileName, $content);
    }

    public function loadEmails()
    {
        if (file_exists($this->fileName)) {
            $content = file_get_contents($this->fileName);
            $this->emails = $this->serializer->deserialize($content, Email::class . '[]', 'json', ['skip_null_values' => true]);
        }
    }
}
