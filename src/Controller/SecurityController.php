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

use App\EventListener\FormAuthenticationListener;
use App\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="login")
     * @Template
     */
    public function loginAction(Request $request, FormAuthenticationListener $listener)
    {
        if ($this->isAuthenticated()) {
            return $this->redirectToRoute('dashboard');
        }

        $targetPath = $request->headers->get('referer');

        $form = $this->createForm(
            LoginForm::class,
            null,
            [
                'target_path' => $targetPath,
                'authentication_listener' => $listener,
            ]
        );

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/login_check", name="login_check", options={"i18n"=false})
     */
    public function loginCheckAction()
    {
        throw new \Exception('Undefined method');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception('Undefined method');
    }
}
