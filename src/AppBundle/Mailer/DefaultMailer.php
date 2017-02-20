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
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class DefaultMailer implements Mailer
{
    /** 
     * @var EngineInterface 
     */
    private $templating;

    /** 
     * @var RouterInterface 
     */
    private $router;

    /** 
     * @var \Swift_Mailer 
     */
    private $mailer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /** 
     * @var array 
     */
    private $displayNames;

    /** 
     * @var array 
     */
    private $emails;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $txt;

    /**
     * @var string
     */
    private $html;

    public function __construct(EngineInterface $templating, RouterInterface $router, \Swift_Mailer $mailer, TranslatorInterface $translator, array $displayNames, array $emails)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->displayNames = $displayNames;
        $this->emails = $emails;
        $this->router = $router;
        $this->translator = $translator;
    }

    public function sendConfirmRegistrationEmailTo(User $user)
    {
        $this->sendHtml('registration.confirm', $this->getFullEmailAddress('no-reply'), $user->getEmail(), [
            'url' => $this->generateUrl('register_confirm', [
                'token' => $user->getConfirmationToken()
            ]),
            'user' => $user->getEmail(),
        ]);
    }

    public function sendRegistrationCompletedEmailTo(User $user)
    {
        $this->sendHtml('registration.completed', $this->getFullEmailAddress('no-reply'), $user->getEmail(), [
            'user' => $user->getEmail(),
        ]);
    }

    public function sendResetPasswordRequestEmailTo(User $user)
    {
        $this->sendHtml('reset_password', $this->getFullEmailAddress('no-reply'), $user->getEmail(), [
            'url' => $this->generateUrl('reset_password', [
                'token' => $user->getResetPasswordToken()
            ]),
            'user' => $user->getEmail(),
        ]);
    }

    public function sendInviteAccountantEmailTo(Invite $invite)
    {
        $this->sendHtml('invite.accountant', $this->getFullEmailAddress('no-reply'), $invite->getEmail(), [
            'company' => $invite->getCompany(),
            'accountant' => $invite->getEmail(),
            'url' => $this->generateUrl('invite_view', ['token' => $invite->getToken()]),
        ]);
    }

    public function sendContactUsMail($email, $subject, $body)
    {
        $this->sendHtml('contact_us', $email, $this->getFullEmailAddress('info'), [
            'from' => $email,
            'subject' => $subject,
            'body' => $body
        ]);
    }

    protected function sendHtml($content, $from, $to, array $params = [])
    {
        $subject = $content . '.subject';
        $htmlBody = $content . '.html_body';
        $txtBody = $content . '.txt_body';

        $this->subject = $this->translate($subject, $params);
        $this->txt = $this->translate($txtBody, $params);
        $this->html = $this->templating->render('::email.html.twig', ['body' => $this->translate($htmlBody, $params)]);
        
        $this->sendEmail($from, $to, $params);
    }

    private function getFullEmailAddress($name)
    {
        return [$this->emails[$name] => $this->displayNames[$name]];
    }

    private function generateUrl($route, $routeParams)
    {
        return $this->router->generate($route, $routeParams, Router::ABSOLUTE_URL);
    }

    /**
     * @return string
     */
    private function translate($message, $params)
    {
        $transParams = [];

        foreach ($params as $i => $param) {
            $transParams['%' . $i . '%'] = $param;
        }

        return $this->translator->trans($message, $transParams, 'emails');
    }

    /**
     * @param $from
     * @param $to
     * @param array $params
     * @param $subject
     * @param $txtBody
     * @param $html
     */
    protected function sendEmail($from, $to, array $params)
    {
        /** @var \Swift_Message $message */
        $message = \Swift_Message::newInstance()
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($this->subject)
            ->setBody($this->txt)
            ->addPart($this->html, 'text/html');

        $this->mailer->send($message);
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getTxt()
    {
        return $this->txt;
    }

    public function getHtml()
    {
        return $this->html;
    }
}
