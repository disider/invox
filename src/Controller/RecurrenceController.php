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

use App\Entity\Recurrence;
use App\Form\Filter\RecurrenceFilterForm;
use App\Form\Processor\RecurrenceFormProcessor;
use App\Repository\RecurrenceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recurrences")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class RecurrenceController extends BaseController
{
    /**
     * @Route("", name="recurrences")
     * @Security("is_granted('LIST_RECURRENCES')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $user = $this->getUser();

        if ($user->isSuperadmin()) {
            $filters = [];
        } else {
            $filters = [
                RecurrenceRepository::FILTER_BY_COMPANY => $this->getCurrentCompany(),
                RecurrenceRepository::FILTER_BY_MANAGER => $this->getUser(),
            ];
        }

        $query = $this->getRecurrenceRepository()->findAllQuery($filters);

        $filterForm = $this->buildFilterForm($request, $query, RecurrenceFilterForm::class);

        $pagination = $this->paginate($query, $page, $pageSize);

        return [
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/new", name="recurrence_create")
     * @Security("is_granted('RECURRENCE_CREATE')")
     * @Template
     */
    public function createAction(Request $request, RecurrenceFormProcessor $processor)
    {
        $recurrence = new Recurrence();
        $recurrence->setCompany($this->getCurrentCompany());

        return $this->processForm($request, $processor, $recurrence);
    }

    /**
     * @Route("/{id}/edit", name="recurrence_edit")
     * @Security("is_granted('RECURRENCE_EDIT', recurrence)")
     * @Template
     */
    public function editAction(Request $request, RecurrenceFormProcessor $processor, Recurrence $recurrence)
    {
        return $this->processForm($request, $processor, $recurrence);
    }

    /**
     * @Route("/search", name="recurrence_search")
     */
    public function searchAction(Request $request)
    {
        $customerId = $request->get('customerId');
        $term = $request->get('term');

        $recurrences = $this->getRecurrenceRepository()->search($term, $customerId);

        return $this->serialize(
            [
                'recurrences' => $recurrences,
                'customerId' => $customerId,
                'term' => $term,
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="recurrence_delete")
     * @Security("is_granted('RECURRENCE_DELETE', recurrence)")
     */
    public function deleteAction(Recurrence $recurrence)
    {
        $this->delete($recurrence);

        $this->addFlash('success', 'flash.recurrence.deleted', ['%recurrence%' => $recurrence]);

        return $this->redirectToRoute('recurrences');
    }

    private function processForm(Request $request, RecurrenceFormProcessor $processor, Recurrence $recurrence = null)
    {
        $processor->process($request, $recurrence);

        if ($processor->isValid()) {
            $this->addFlash(
                'success',
                $processor->isNew() ? 'flash.recurrence.created' : 'flash.recurrence.updated',
                ['%recurrence%' => $processor->getData()]
            );

            if ($processor->isRedirectingTo(RecurrenceFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('recurrences');
            }

            return $this->redirectToRoute(
                'recurrence_edit',
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
