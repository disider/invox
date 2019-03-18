<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AdminBundle\Controller;

use App\EventListener\FormAuthenticationListener;
use App\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="admin_login")
     * @Template
     */
    public function loginAction(Request $request, FormAuthenticationListener $listener)
    {
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
     * @Route("/login_check", name="admin_login_check")
     */
    public function checkAction()
    {
        throw new \Exception('Undefined method');
    }

    /**
     * @Route("/logout", name="admin_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('Undefined method');
    }
}
