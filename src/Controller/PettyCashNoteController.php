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

use App\Entity\PettyCashNote;
use App\Form\Filter\PettyCashNoteFilterForm;
use App\Form\Processor\PettyCashNoteFormProcessor;
use App\Helper\ProtocolGenerator;
use App\Repository\PettyCashNoteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/petty-cash-notes")
 */
class PettyCashNoteController extends BaseController
{
    /**
     * @Route("", name="petty_cash_notes")
     * @Security("is_granted('LIST_PETTY_CASH_NOTES')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $filters = [];

        $user = $this->getUser();

        if ($user->isSuperadmin()) {
            // Do not filter...
        } elseif ($this->isCurrentAccountant()) {
            $filters[PettyCashNoteRepository::FILTER_BY_ACCOUNTANT] = $user;
        } else {
            $filters[PettyCashNoteRepository::FILTER_BY_MANAGER] = $user;
        }

        $filters[PettyCashNoteRepository::FILTER_BY_COMPANY] = $this->getCurrentCompany();
        $query = $this->getPettyCashNoteRepository()->findAllQuery($filters, $page, $pageSize);

        $filterForm = $this->buildFilterForm($request, $query, PettyCashNoteFilterForm::class);

        $pagination = $this->paginate($query, $page, $pageSize, 'note.recordedAt', 'desc');

        $totalAmount = $this->getPettyCashNoteRepository()->getTotalAmount($query);

        return [
            'totalAmount' => $totalAmount,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/new", name="petty_cash_note_create")
     * @Security("is_granted('PETTY_CASH_NOTE_CREATE')")
     * @Template
     */
    public function createAction(
        Request $request,
        ProtocolGenerator $protocolGenerator,
        PettyCashNoteFormProcessor $processor
    ) {
        if (!$this->canManageCurrentCompany()) {
            throw $this->createAccessDeniedException('Cannot manage petty cash for '.$this->getCurrentCompany());
        }

        $ref = $this->getCurrentCompany()
            ? $protocolGenerator->generate(PettyCashNote::class, $this->getCurrentCompany(), date('Y'))
            : 1;

        $pettyCashNote = PettyCashNote::createEmpty($ref, $this->getCurrentCompany());

        return $this->processForm($request, $processor, $pettyCashNote);
    }

    /**
     * @Route("/{id}/edit", name="petty_cash_note_edit")
     * @Security("is_granted('PETTY_CASH_NOTE_EDIT', pettyCashNote)")
     * @Template
     */
    public function editAction(Request $request, PettyCashNoteFormProcessor $processor, PettyCashNote $pettyCashNote)
    {
        return $this->processForm($request, $processor, $pettyCashNote);
    }

    /**
     * @Route("/{id}/delete", name="petty_cash_note_delete")
     * @Security("is_granted('PETTY_CASH_NOTE_DELETE', pettyCashNote)")
     */
    public function deleteAction(PettyCashNote $pettyCashNote)
    {
        $this->delete($pettyCashNote);

        $this->addFlash('success', 'flash.petty_cash_note.deleted', ['%petty_cash_note%' => $pettyCashNote]);

        return $this->redirectToRoute('petty_cash_notes');
    }

    /**
     * @Route("/{id}/view", name="petty_cash_note_view")
     * @Security("is_granted('PETTY_CASH_NOTE_VIEW', pettyCashNote)")
     * @Template
     */
    public function viewAction(PettyCashNote $pettyCashNote)
    {
        return [
            'pettyCashNote' => $pettyCashNote,
        ];
    }

    private function processForm(Request $request, PettyCashNoteFormProcessor $processor, PettyCashNote $pettyCashNote)
    {
        $processor->process($request, $pettyCashNote);

        if ($processor->isValid()) {
            $this->addFlash(
                'success',
                $processor->isNew() ? 'flash.petty_cash_note.created' : 'flash.petty_cash_note.updated',
                ['%petty_cash_note%' => $processor->getData()]
            );

            if ($processor->isRedirectingTo(PettyCashNoteFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('petty_cash_notes');
            }

            return $this->redirectToRoute(
                'petty_cash_note_edit',
                [
                    'id' => $processor->getData()->getId(),
                ]
            );
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
