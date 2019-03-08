<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Controller;

use App\Entity\Invite;
use App\Mailer\MailerInterface;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tests")
 */
class TestController extends BaseController
{
    /**
     * @Route("/emails/confirm-registration", name="test_confirm_registration_email")
     * @Template("default/test_email.html.twig")
     */
    public function confirmRegistrationEmailAction(UserRepository $userRepository, MailerInterface $mailer)
    {
        $user = $userRepository->findLast();
        $user->setConfirmationToken('12345678');

        $mailer->sendConfirmRegistrationEmailTo($user);

        return [
            'subject' => $mailer->getSubject(),
            'txt' => $mailer->getTxt(),
            'html' => $mailer->getHtml()
        ];
    }

    /**
     * @Route("/emails/registration-completed", name="test_registration_completed_email")
     * @Template("default/test_email.html.twig")
     */
    public function registrationCompletedEmailAction(UserRepository $userRepository, MailerInterface $mailer)
    {
        $user = $userRepository->findLast();

        $mailer->sendRegistrationCompletedEmailTo($user);

        return [
            'subject' => $mailer->getSubject(),
            'txt' => $mailer->getTxt(),
            'html' => $mailer->getHtml()
        ];
    }

    /**
     * @Route("/emails/reset-password", name="test_reset_password_email")
     * @Template("default/test_email.html.twig")
     */
    public function resetPasswordEmailAction(UserRepository $userRepository, MailerInterface $mailer)
    {
        $user = $userRepository->findLast();
        $user->setResetPasswordToken('12345678');

        $mailer->sendResetPasswordRequestEmailTo($user);

        return [
            'subject' => $mailer->getSubject(),
            'txt' => $mailer->getTxt(),
            'html' => $mailer->getHtml()
        ];
    }

    /**
     * @Route("/emails/invite-accountant", name="test_invite_accountant_email")
     * @Template("default/test_email.html.twig")
     */
    public function inviteAccountantEmailAction(UserRepository $userRepository, MailerInterface $mailer)
    {
        $user = $userRepository->findLast();
        $invite = new Invite();
        $invite->setCompany($user->getDefaultCompany());
        $invite->setEmail('accountant@example.com');
        $invite->setToken('12345678');

        $mailer->sendInviteAccountantEmailTo($invite);

        return [
            'subject' => $mailer->getSubject(),
            'txt' => $mailer->getTxt(),
            'html' => $mailer->getHtml()
        ];
    }

    /**
     * @Route("/render-html/{html}", name="test_render_html")
     */
    public function renderHtml($html)
    {
        return new Response($html);
    }
}
