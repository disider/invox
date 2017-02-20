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

use AppBundle\Entity\User;
use AppBundle\Form\RequestResetPasswordForm;
use AppBundle\Form\ResetPasswordForm;
use AppBundle\Generator\TokenGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reset-password")
 */
class ResetPasswordController extends BaseController
{
    /**
     * @Route("", name="reset_password_request")
     * @Template
     */
    public function requestResetPasswordAction(Request $request)
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createForm(RequestResetPasswordForm::class, null, [
            'data_class' => $this->getUserClass(),
        ]);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var User $user */
                $user = $form->getData();

                $user = $this->getUserRepository()->findOneByEmail($user->getUsername());

                if ($user) {
                    $user->setResetPasswordToken(TokenGenerator::generateToken());
                    $user->setPasswordRequestedAt(new \DateTime());

                    $this->save($user);

                    $mailer = $this->getMailer();

                    $mailer->sendResetPasswordRequestEmailTo($user);
                }

                return $this->redirectToRoute('reset_password_request_sent');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/request-sent", name="reset_password_request_sent")
     * @Template
     */
    public function resetPasswordRequestSentAction()
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        return [];
    }

    /**
     * @Route("/reset/{token}", name="reset_password")
     * @Template
     */
    public function resetPasswordAction(Request $request, $token)
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        $user = $this->getUserRepository()->findOneByResetPasswordToken($token);

        if (!$user) {
            throw $this->createNotFoundException(sprintf('User not found for reset token: %s', $token));
        }

        $form = $this->createForm(ResetPasswordForm::class, $user, [
            'data_class' => $this->getUserClass(),
        ]);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $user->setResetPasswordToken(null);
                $user->setPasswordRequestedAt(null);

                $userManager = $this->getUserManager();
                $userManager->updateUser($user);

                $this->authenticateUser($user);

                $this->addFlash('success', 'flash.reset_password.completed');

                return $this->redirectToRoute('dashboard');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
