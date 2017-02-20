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

use AppBundle\Entity\Invite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/invites")
 */
class InviteController extends BaseController
{
    /**
     * @Route("", name="invites")
     * @Security("is_granted('LIST_INVITES')")
     * @Template
     */
    public function indexAction()
    {
        $invites = $this->getUser()->getReceivedInvites();

        return [
            'invites' => $invites,
        ];
    }

    /**
     * @Route("/{token}/view", name="invite_view")
     * @Template
     */
    public function viewAction(Invite $invite)
    {
        $email = $invite->getEmail();

        if(!$this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if($this->getUserRepository()->findOneByEmail($email)) {
                return $this->redirectToRoute('login');
            }

            return $this->redirectToRoute('register', ['email' => $email]);
        }

        if($email != $this->getUser()->getEmail()) {
            throw $this->createAccessDeniedException('Cannot access this invite');
        }


        return [
            'invite' => $invite,
        ];
    }

    /**
     * @Route("/{id}/delete", name="invite_delete")
     * @Security("is_granted('INVITE_DELETE', invite)")
     */
    public function deleteAction(Invite $invite)
    {
        $this->delete($invite);

        return $this->redirectToRoute('company_accountant', [
            'id' => $invite->getCompany()->getId()
        ]);
    }

    /**
     * @Route("/{token}/accept", name="invite_accept")
     * @Security("is_granted('INVITE_ACCEPT', invite)")
     */
    public function acceptAction(Invite $invite)
    {
        $accountant = $invite->getReceiver();

        $company = $invite->getCompany();
        $accountant->addAccountedCompany($company);

        $this->save($company);
        $this->delete($invite);

        $this->addFlash('success', 'flash.invite.accepted', ['%company%' => $company]);

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/{token}/refuse", name="invite_refuse")
     * @Security("is_granted('INVITE_REFUSE', invite)")
     */
    public function refuseAction(Invite $invite)
    {
        $this->delete($invite);

        $this->addFlash('info', 'flash.invite.refused', ['%company%' => $invite->getCompany()]);

        return $this->redirectToRoute('dashboard');
    }
}
