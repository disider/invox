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

use AppBundle\Entity\WorkingNote;
use AppBundle\Form\Filter\WorkingNoteFilterForm;
use AppBundle\Form\Processor\WorkingNoteFormProcessor;
use AppBundle\Repository\WorkingNoteRepository;
use Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/working-notes")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class WorkingNoteController extends BaseController
{

    /**
     * @Route("", name="working_notes")
     * @Security("is_granted('LIST_WORKING_NOTES')")
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
        } else {
            $filters[WorkingNoteRepository::FILTER_BY_MANAGER] = $user;
            $filters[WorkingNoteRepository::FILTER_BY_SALES_AGENT] = $user;
        }

        $query = $this->getWorkingNoteRepository()->findAllQuery($filters, $page, $pageSize);

        $filterForm = $this->buildFilterForm($request, $query, WorkingNoteFilterForm::class);

        $pagination = $this->paginate($query, $page, $pageSize);

        return [
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/new", name="working_note_create")
     * @Security("is_granted('WORKING_NOTE_CREATE')")
     * @Template
     */
    public function createAction(Request $request, WorkingNoteFormProcessor $processor)
    {
        $workingNote = new WorkingNote();

        $company = $this->getCurrentCompany();

        if ($company) {
            $workingNote->setCompany($company);
        }

        return $this->processForm($request, $processor, $workingNote);
    }

    /**
     * @Route("/{id}/edit", name="working_note_edit")
     * @Security("is_granted('WORKING_NOTE_EDIT', workingNote)")
     * @Template
     */
    public function editAction(Request $request, WorkingNoteFormProcessor $processor, WorkingNote $workingNote)
    {
        return $this->processForm($request, $processor, $workingNote);
    }

    /**
     * @Route("/{id}/delete", name="working_note_delete")
     * @Security("is_granted('WORKING_NOTE_DELETE', workingNote)")
     */
    public function deleteAction(WorkingNote $workingNote, WorkingNoteRepository $repository)
    {
        $repository->delete($workingNote);

        $this->addFlash('success', 'flash.working_note.deleted');

        return $this->redirectToRoute('working_notes');
    }

    /**
     * @Route("/{id}/view", name="working_note_view")
     * @Security("is_granted('WORKING_NOTE_VIEW', workingNote)")
     * @Template
     */
    public function viewAction(Request $request, WorkingNote $workingNote)
    {
        return [
            'workingNote' => $workingNote,
            'showAsHtml' => $request->get('showAsHtml', false) !== false,
            'debug' => $this->getParameter('kernel.debug') !== false,
        ];
    }

    /**
     * @Route("/{id}/render", name="working_note_render")
     * @Security("is_granted('WORKING_NOTE_RENDER', workingNote)")
     */
    public function renderAction(Request $request, WorkingNote $workingNote)
    {
        return $this->renderWorkingNote($request, $workingNote, 'inline;');
    }

    /**
     * @Route("/{id}/print", name="working_note_print")
     * @Security("is_granted('WORKING_NOTE_PRINT', workingNote)")
     */
    public function printAction(Request $request, WorkingNote $workingNote)
    {
        $mode = sprintf('attachment; filename="%s"', $this->formatFileName($workingNote));

        return $this->renderWorkingNote($request, $workingNote, $mode);
    }

    private function renderWorkingNote(Request $request, WorkingNote $workingNote, $mode)
    {
        $showAsHtml = $request->get('showAsHtml', false) !== false;

        $this->disableProfiler();

        $header = $this->renderPartialView('header');
        $content = $this->renderPartialView('content', ['workingNote' => $workingNote]);
        $footer = $this->renderPartialView('footer');

        if ($showAsHtml) {
            try {
                $params = [
                    'header' => $header,
                    'content' => $content,
                    'footer' => $footer,
                    'showAsHtml' => $showAsHtml];

                return $this->render('AppBundle:working_note:render.html.twig', $params);
            } catch (\Exception $exc) {
                return $this->render('AppBundle:document:preview_error.html.twig', ['exception' => $exc]);
            }
        }

        /** @var LoggableGenerator $pdf */
        $pdf = $this->get('knp_snappy.pdf');

        $options = [
            'encoding' => 'utf-8',
            'header-html' => $this->renderPdfLayout($header),
            'footer-html' => $this->renderPdfLayout($footer),
            'header-spacing' => 2,
        ];

        return new Response($pdf->getOutputFromHtml($this->renderPdfLayout($content), $options), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $mode,
        ]);
    }

    private function renderPartialView($partial, $options = [])
    {
        $options['company'] = $this->getCurrentCompany();

        return $this->renderView('AppBundle:working_note:_pdf_' . $partial . '.html.twig', $options);
    }

    private function formatFileName(WorkingNote $workingNote)
    {
        return sprintf('working-note-%s.pdf', strtolower(str_replace(' ', '-', $workingNote->getCode())));
    }

    private function renderPdfLayout($content)
    {
        return $this->renderView(
            'AppBundle:working_note:pdf_layout.html.twig', [
                'content' => $content
            ]
        );
    }

    protected function processForm(Request $request, WorkingNoteFormProcessor $processor, WorkingNote $workingNote = null)
    {
        $processor->process($request, $workingNote);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.working_note.created' : 'flash.working_note.updated');

            if ($processor->isRedirectingTo(WorkingNoteFormProcessor::REDIRECT_TO_LIST))
                return $this->redirectToRoute('working_notes');

            return $this->redirectToRoute('working_note_edit', ['id' => $processor->getData()->getId()]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView()
        ];
    }

}
