<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Processor;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\Invite;
use AppBundle\Entity\Repository\CustomerRepository;
use AppBundle\Entity\Repository\EntityRepository;
use AppBundle\Form\InviteForm;
use AppBundle\Mailer\Mailer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class InviteFormProcessor extends AbstractFormProcessor
{
    /**
     * @var EntityRepository
     */
    private $inviteRepository;

    /**
     * @var Invite
     */
    private $invite;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var boolean
     */
    private $hasSentInvite;

    public function __construct(EntityRepository $documentRepository, Mailer $mailer, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($formFactory, $tokenStorage);

        $this->inviteRepository = $documentRepository;
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

    public function hasSentInvite() {
        return $this->hasSentInvite;
    }

    protected function handleRequest(Request $request)
    {
        if ($request->isMethod('POST')) {
            $form = $this->getForm();

            $form->handleRequest($request);

            if ($this->isValid()) {
                $this->invite = $form->getData();
                if($this->inviteRepository->findOneByEmail($this->invite->getEmail())) {
                    $this->hasSentInvite = false;                    
                }
                else {
                    $this->inviteRepository->save($this->invite);
                    $this->hasSentInvite = true;

                    $this->mailer->sendInviteAccountantEmailTo($this->invite);
                }
            }
        }
    }
}
