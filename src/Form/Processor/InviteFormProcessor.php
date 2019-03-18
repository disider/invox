<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Processor;

use App\Entity\Invite;
use App\Form\InviteForm;
use App\Mailer\MailerInterface;
use App\Repository\InviteRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class InviteFormProcessor extends AbstractFormProcessor
{
    /**
     * @var InviteRepository
     */
    private $inviteRepository;

    /**
     * @var Invite
     */
    private $invite;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var boolean
     */
    private $hasSentInvite;

    public function __construct(
        InviteRepository $inviteRepository,
        MailerInterface $mailer,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($formFactory, $tokenStorage);

        $this->inviteRepository = $inviteRepository;
        $this->mailer = $mailer;
    }

    protected function getFormClass()
    {
        return InviteForm::class;
    }

    protected function getFormOptions()
    {
        return [];
    }

    public function getInvite()
    {
        return $this->invite;
    }

    public function hasSentInvite()
    {
        return $this->hasSentInvite;
    }

    protected function handleRequest(Request $request)
    {
        if ($request->isMethod('POST')) {
            $form = $this->getForm();

            $form->handleRequest($request);

            if ($this->isValid()) {
                $this->invite = $form->getData();
                if ($this->inviteRepository->findOneByEmail($this->invite->getEmail())) {
                    $this->hasSentInvite = false;
                } else {
                    $this->inviteRepository->save($this->invite);
                    $this->hasSentInvite = true;

                    $this->mailer->sendInviteAccountantEmailTo($this->invite);
                }
            }
        }
    }
}
