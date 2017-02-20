<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordForm;
use AppBundle\Form\ProfileForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/profile")
 */
class ProfileController extends BaseController
{
    /**
     * @Route("", name="profile_edit")
     * @Template
     */
    public function editAction(Request $request)
    {
        return $this->processForm($request);
    }

    /**
     * @Route("/change-password", name="profile_change_password")
     * @Template
     */
    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(ChangePasswordForm::class, $this->getUser(), [
            'data_class' => $this->getUserClass(),
        ]);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);


            if ($form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $userManager = $this->getUserManager();

                $userManager->updateUser($user);

                $this->addFlash('success', 'flash.password.updated');

                return $this->redirectToRoute('profile_edit');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    private function processForm(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileForm::class, $user, [
            'data_class' => $this->getUserClass(),
        ]);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var User $user */
                $user = $form->getData();

                $this->save($user);

                $this->addFlash(
                    'success', 'flash.profile.updated'
                );

                if ($form->get('saveAndClose')->isClicked()) {
                    return $this->redirectToRoute('dashboard');
                }

                return $this->redirectToRoute('profile_edit');
            }
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }
}
