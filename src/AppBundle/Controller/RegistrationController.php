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

use AppBundle\Entity\Manager\UserManager;
use AppBundle\Entity\User;
use AppBundle\Form\RegistrationForm;
use AppBundle\Generator\TokenGenerator;
use AppBundle\Mailer\MailerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/register")
 */
class RegistrationController extends BaseController
{
    /**
     * @Route("", name="register")
     * @Template
     */
    public function registerAction(Request $request, MailerInterface $mailer, UserManager $userManager)
    {
        if (!$this->getParameter('enable_registration')) {
            return $this->redirectToRoute('dashboard');
        }

        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        $email = $request->get('email');
        $user = new User();
        $user->setEmail($email);

        $form = $this->createForm(RegistrationForm::class, $user);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $user->addRole(User::ROLE_OWNER);
                $user->setConfirmationToken(TokenGenerator::generateToken());

                $userManager->updateUser($user);

                $mailer->sendConfirmRegistrationEmailTo($user);

                return $this->redirectToRoute('register_request_confirmation');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/request-confirmation", name="register_request_confirmation")
     * @Template
     */
    public function requestRegistrationConfirmationAction()
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        return [];
    }

    /**
     * @Route("/{email}/resend-confirmation", name="register_resend_confirmation")
     */
    public function resendRegistrationConfirmationAction(User $user, MailerInterface $mailer)
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        if ($user->isEnabled()) {
            throw new BadRequestHttpException('User is already enabled');
        }

        $mailer->sendConfirmRegistrationEmailTo($user);

        return $this->redirectToRoute('register_request_confirmation');
    }

    /**
     * @Route("/confirm/{token}", name="register_confirm")
     */
    public function confirmRegistrationAction($token, MailerInterface $mailer)
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        /** @var User $user */
        $user = $this->getUserRepository()->findOneByConfirmationToken($token);
        if (!$user) {
            throw new NotFoundHttpException(sprintf('User for token: %s not found', $token));
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->save($user);

        $mailer->sendRegistrationCompletedEmailTo($user);

        $this->authenticateUser($user);

        $this->addFlash('success', 'flash.registration.confirmed');

        return $this->redirectToRoute('dashboard');
    }
}
