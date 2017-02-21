<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Controller;

use AppBundle\DependencyInjection\DynamicConfiguration;
use AppBundle\Entity\Invite;
use AppBundle\Form\RegisterForm;
use AppBundle\Form\ResetPasswordRequestForm;
use AppBundle\Mailer\MailerInterface;
use LegacyBundle\Entity\Country;
use LegacyBundle\Entity\Coupon;
use LegacyBundle\Entity\Repository\DefaultRepository;
use LegacyBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tests")
 */
class TestController extends BaseController
{
    /**
     * @Route("/emails/confirm-registration", name="test_confirm_registration_email")
     * @Template("AppBundle:Default:testEmail.html.twig")
     */
    public function confirmRegistrationEmailAction()
    {
        $user = $this->get('user_repository')->findLast();
        $user->setConfirmationToken('12345678');

        $mailer = $this->getMailer();
        $mailer->sendConfirmRegistrationEmailTo($user);

        return [
            'subject' => $mailer->getSubject(),
            'txt' => $mailer->getTxt(),
            'html' => $mailer->getHtml()
        ];
    }

    /**
     * @Route("/emails/registration-completed", name="test_registration_completed_email")
     * @Template("AppBundle:Default:testEmail.html.twig")
     */
    public function registrationCompletedEmailAction()
    {
        $user = $this->get('user_repository')->findLast();

        $mailer = $this->getMailer();
        $mailer->sendRegistrationCompletedEmailTo($user);

        return [
            'subject' => $mailer->getSubject(),
            'txt' => $mailer->getTxt(),
            'html' => $mailer->getHtml()
        ];
    }

    /**
     * @Route("/emails/reset-password", name="test_reset_password_email")
     * @Template("AppBundle:Default:testEmail.html.twig")
     */
    public function resetPasswordEmailAction()
    {
        $user = $this->get('user_repository')->findLast();
        $user->setResetPasswordToken('12345678');

        $mailer = $this->getMailer();
        $mailer->sendResetPasswordRequestEmailTo($user);

        return [
            'subject' => $mailer->getSubject(),
            'txt' => $mailer->getTxt(),
            'html' => $mailer->getHtml()
        ];
    }

    /**
     * @Route("/emails/invite-accountant", name="test_invite_accountant_email")
     * @Template("AppBundle:Default:testEmail.html.twig")
     */
    public function inviteAccountantEmailAction()
    {
        $user = $this->get('user_repository')->findLast();
        $invite = new Invite();
        $invite->setCompany($user->getDefaultCompany());
        $invite->setEmail('accountant@example.com');
        $invite->setToken('12345678');

        $mailer = $this->getMailer();
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
