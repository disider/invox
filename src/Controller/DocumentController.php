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

use App\Builder\DocumentBuilder;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\Recurrence;
use App\Form\Processor\DocumentFormProcessor;
use App\Helper\ProtocolGenerator;
use App\Model\DocumentType;
use App\Repository\DocumentRepository;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documents")
 */
class DocumentController extends BaseController
{
    /**
     * @Route("/new", name="document_create")
     * @Security("is_granted('DOCUMENT_CREATE')")
     * @Template
     */
    public function createAction(Request $request, ProtocolGenerator $generator, DocumentFormProcessor $processor)
    {
        $company = $this->getCurrentCompany();

        if (!$this->canManageCurrentCompany()) {
            throw $this->createAccessDeniedException('Cannot manage documents for '.$company);
        }

        $type = $request->get('type', DocumentType::QUOTE);
        $year = $request->get('year', date('Y'));
        $ref = $this->generateRef($generator, $company, $type, $year);

        $document = Document::createEmpty(
            $type,
            Document::NO_DIRECTION,
            $ref,
            date('Y'),
            $company
        );
        $document->setLanguage($this->getCurrentLocale());
        $document->setDocumentTemplate($company->getFirstDocumentTemplate());

        $this->applyCustomer($request, $company, $document);
        $this->applyRecurrence($request, $company, $document);

        return $this->processForm($request, $processor, $document);
    }

    /**
     * @Route("/{id}/edit", name="document_edit")
     * @Security("is_granted('DOCUMENT_EDIT', document)")
     * @Template
     */
    public function editAction(Request $request, DocumentFormProcessor $processor, Document $document)
    {
        return $this->processForm($request, $processor, $document);
    }

    /**
     * @Route("/{id}/delete", name="document_delete")
     * @Security("is_granted('DOCUMENT_DELETE', document)")
     */
    public function deleteAction(Document $document)
    {
        $this->getCompanyRepository()->delete($document);

        $this->addFlash('success', 'flash.document.deleted', ['%document%' => $document]);

        return $this->redirectToRouteByDocument($document);
    }

    /**
     * @Route("/{id}/view", name="document_view")
     * @Security("is_granted('DOCUMENT_VIEW', document)")
     * @Template
     */
    public function viewAction(Request $request, Document $document)
    {
        return [
            'document' => $document,
            'showAsHtml' => $request->get('showAsHtml', false) !== false,
            'debug' => $this->getParameter('kernel.debug') !== false,
        ];
    }

    /**
     * @Route("/{id}/copy", name="document_copy")
     * @Security("is_granted('DOCUMENT_COPY', document)")
     */
    public function copyAction(Document $document, ProtocolGenerator $protocolGenerator)
    {
        $ref = $protocolGenerator->generate(Document::class, $document->getCompany(), $document->getYear());

        $document = $document->copy();
        $document->setRef($ref);

        $this->save($document);

        $this->addFlash(
            'success',
            'flash.document.copied',
            ['%document%' => $document]
        );

        return $this->redirectToRoute('document_edit', ['id' => $document->getId()]);
    }

    /**
     * @Route("/{id}/render", name="document_render")
     */
    public function renderAction(Request $request, DocumentBuilder $builder, Document $document, Pdf $pdf)
    {
        return $this->renderDocument($request, $builder, $pdf, $document, 'inline;');
    }

    /**
     * @Route("/{id}/print", name="document_print")
     * @Security("is_granted('DOCUMENT_PRINT', document)")
     */
    public function printAction(Request $request, DocumentBuilder $builder, Document $document, Pdf $pdf)
    {
        $mode = sprintf('attachment; filename="%s"', $this->formatFileName($document));

        return $this->renderDocument($request, $builder, $pdf, $document, $mode);
    }

    /**
     * @Route("/{id}/reload", name="document_reload")
     */
    public function reloadAction(Request $request, Document $document)
    {
        $document->copyCompanyDetails();
        $this->getDocumentRepository()->save($document);

        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }

    /**
     * @Route("/search", name="document_search")
     */
    public function searchAction(Request $request)
    {
        $term = $request->get('term');

        $filters = [];

        if ($type = $request->get('type')) {
            $filters[DocumentRepository::FILTER_BY_TYPE] = $type;
        }

        if ($status = $request->get('status')) {
            $filters[DocumentRepository::FILTER_BY_STATUS] = $status;
        }

        $records = $this->getDocumentRepository()->search($term, $this->getCurrentCompany(), $filters);

        $documents = [];

        if (($noteId = $request->get('currentNoteId'))) {
            $pettyCashNote = $this->getPettyCashNoteRepository()->findOneById($noteId);

            /** @var Document $document */
            foreach ($records as $document) {
                if ($document->hasUnpaidAmountExcept($pettyCashNote)) {
                    $document->removePettyCashNote($pettyCashNote);
                    $documents[] = $document;
                }
            }
        } else {
            $documents = $records;
        }

        return $this->serialize(
            [
                'documents' => $documents,
            ]
        );
    }

    /**
     * @Route("/cost-centers/search", name="document_cost_centers_search")
     */
    public function searchCostCentersAction(Request $request)
    {
        $term = $request->get('term');

        $costCenters = $this->getDocumentCostCenterRepository()->search($term, $this->getCurrentCompany());

        return $this->serialize(
            [
                'costCenters' => $costCenters,
            ]
        );
    }

    /**
     * @Route("/generate-ref", name="document_generate_ref")
     */
    public function generateRefAction(Request $request, ProtocolGenerator $generator)
    {
        $year = $request->get('year', date('Y'));
        $type = $request->get('type');

        return $this->serialize(
            [
                'ref' => $this->generateRef($generator, $this->getCurrentCompany(), $type, $year),
            ]
        );
    }

    private function processForm(Request $request, DocumentFormProcessor $processor, Document $document)
    {
        $processor->process($request, $document);

        if ($processor->isValid() && $processor->isSaving()) {
            $this->addFlash(
                'success',
                $processor->isNew() ? 'flash.document.created' : 'flash.document.updated',
                ['%document%' => $processor->getDocument()]
            );

            if ($processor->isRedirectingTo(DocumentFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRouteByDocument($document);
            }

            return $this->redirectToRoute(
                'document_edit',
                [
                    'id' => $processor->getDocument()->getId(),
                ]
            );
        }

        $form = $processor->getForm();

        return [
            'document' => $document,
            'form' => $form->createView(),
            'debug' => $this->getParameter('kernel.debug') !== false,
        ];
    }

    private function renderDocument(Request $request, DocumentBuilder $builder, Pdf $pdf, Document $document, $mode)
    {
        $showAsHtml = $request->get('showAsHtml', false) !== false;

        $request->setLocale($document->getLanguage());
        $this->setLocale($document->getLanguage());

        $this->disableProfiler();

        if ($showAsHtml) {
            try {
                $params = [
                    'template' => $document->getDocumentTemplate(),
                    'header' => $this->renderSection($builder, $document, 'header'),
                    'footer' => $this->renderSection($builder, $document, 'footer'),
                    'content' => $this->renderSection($builder, $document, 'content'),
                    'showAsHtml' => $showAsHtml,
                ];

                return $this->render('document/render.html.twig', $params);
            } catch (\Exception $exc) {
                return $this->render('document/preview_error.html.twig', ['exception' => $exc]);
            }
        }

        $body = $this->renderPdfLayout($builder, $document, 'content');

        $headerHtml = $this->renderPdfLayout($builder, $document, 'header');
        $footerHtml = $this->renderPdfLayout($builder, $document, 'footer');

        $options = [
            'encoding' => 'utf-8',
            'header-html' => $headerHtml,
            'footer-html' => $footerHtml,
            'header-spacing' => 2,
        ];

        return new Response(
            $pdf->getOutputFromHtml($body, $options), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => $mode,
            ]
        );
    }

    private function formatFileName(Document $document)
    {
        return sprintf(
            '%s-%s-%s.pdf',
            $document->getType(),
            $document->formatRef('-'),
            strtolower(str_replace(' ', '-', $document->getCustomerName()))
        );
    }

    private function renderPdfLayout(DocumentBuilder $builder, Document $document, $partial)
    {
        return $this->renderView(
            'document/pdf_layout.html.twig',
            [
                'template' => $document->getDocumentTemplate(),
                'content' => $this->renderSection($builder, $document, $partial),
            ]
        );
    }

    private function renderSection(DocumentBuilder $builder, Document $document, $section)
    {
        return $builder->build($document, $section);
    }

    /**
     * @param Document $document
     * @return RedirectResponse
     */
    private function redirectToRouteByDocument(Document $document)
    {
        return $this->redirectToRoute($document->getType().'s');
    }

    private function generateRef(ProtocolGenerator $generator, $company, $type, $year)
    {
        if (!$company) {
            return 1;
        }

        $filters = [
            DocumentRepository::FILTER_BY_TYPE => $type,
        ];

        if ($type == DocumentType::INVOICE || $type == DocumentType::CREDIT_NOTE) {
            $filters[DocumentRepository::FILTER_BY_DIRECTION] = Document::OUTGOING;
        }

        return $generator->generate(
            Document::class,
            $this->getCurrentCompany(),
            $year,
            $filters
        );
    }

    private function applyCustomer(Request $request, Company $company, Document $document)
    {
        if ($request->query->has('customerId')) {
            $customerId = $request->query->get('customerId');
            if ($this->getUser()->isSuperadmin()) {
                $customer = $this->getCustomerRepository()->findOneById($customerId);
            } else {
                $customer = $this->getCustomerRepository()->findOneByIdAndCompany($customerId, $company);
            }

            if (!$customer) {
                throw $this->createNotFoundException(sprintf('Customer %d not found', $customerId));
            }

            $document->setLinkedCustomer($customer);
            $document->copyCustomerDetails();
        }
    }

    private function applyRecurrence(Request $request, Company $company, Document $document)
    {
        if ($request->query->has('recurrenceId')) {
            $recurrenceId = $request->query->get('recurrenceId');
            /** @var Recurrence $recurrence */
            $recurrence = $this->getRecurrenceRepository()->findOneByIdAndCompany($recurrenceId, $company);

            if (!$recurrence) {
                throw $this->createNotFoundException(sprintf('Recurrence %d not found', $recurrenceId));
            }

            $document->setType(DocumentType::INVOICE);
            $document->setDirection($recurrence->getDirection());
            $document->setLinkedCustomer($recurrence->getCustomer());
            $document->copyCustomerDetails();
            $document->setRecurrence($recurrence);
        }
    }

}
