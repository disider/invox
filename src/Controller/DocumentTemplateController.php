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
use App\Entity\DocumentTemplate;
use App\Entity\DocumentTemplatePerCompany;
use App\Form\Processor\DocumentTemplateFormProcessor;
use App\Repository\DocumentTemplateRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document-templates")
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class DocumentTemplateController extends BaseController
{
    /**
     * @Route("", name="document_templates")
     * @Template
     */
    public function indexAction(Request $request, DocumentTemplateRepository $repository)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $repository->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize, 'template.name', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="document_template_create")
     * @Template
     */
    public function createAction(Request $request, DocumentTemplateFormProcessor $processor)
    {
        return $this->processForm($request, $processor);
    }

    /**
     * @Route("/{id}/edit", name="document_template_edit")
     * @Template
     */
    public function editAction(Request $request, DocumentTemplateFormProcessor $processor, DocumentTemplate $documentTemplate)
    {
        return $this->processForm($request, $processor, $documentTemplate);
    }

    /**
     * @Route("/{id}/delete", name="document_template_delete")
     */
    public function deleteAction(DocumentTemplate $documentTemplate)
    {
        $this->delete($documentTemplate);
        $this->addFlash('success', 'flash.document_template.deleted', ['%document_template%' => $documentTemplate]);

        return $this->redirectToRoute('document_templates');
    }

    /**
     * @Route("/{id}/restore", name="document_template_restore")
     */
    public function restoreAction(DocumentTemplate $documentTemplate)
    {
        $templates = $this->getDocumentTemplatePerCompanyRepository()->findBy(['documentTemplate' => $documentTemplate]);

        /** @var DocumentTemplatePerCompany $template */
        foreach ($templates as $template) {
            $template->copyDocumentTemplateDetails();
            $this->save($template);
        }

        $this->addFlash('success', 'flash.document_template.restored', ['%document_template%' => $documentTemplate]);

        return $this->redirectToRoute('document_templates');
    }

    /**
     * @Route("/{id}/preview", name="document_template_preview")
     * @Template
     */
    public function previewAction(DocumentTemplate $documentTemplate)
    {
        return [
            'documentTemplate' => $documentTemplate
        ];
    }

    /**
     * @Route("/{id}/render", name="document_template_render")
     * @Template("document_template_per_company/render.html.twig")
     */
    public function renderAction(Request $request, DocumentBuilder $builder, DocumentTemplate $documentTemplate)
    {
        $locale = $this->getCurrentLocale();
        $request->setLocale($locale);
        $this->setLocale($locale);

        $this->disableProfiler();
        $company = $this->getCurrentCompany();

        $documentTemplatePerCompany = $this->createDocumentTemplatePerCompany($company, $documentTemplate);

        $document = Document::createForTestingTemplates($company, $documentTemplatePerCompany, $locale);

        try {
            $params = [
                'template' => $documentTemplatePerCompany,
                'header' => $this->renderSection($builder, $document, 'header'),
                'footer' => $this->renderSection($builder, $document, 'footer'),
                'content' => $this->renderSection($builder, $document, 'content')
            ];

            return $this->render('document_template_per_company/render.html.twig', $params);
        } catch (\Exception $exc) {
            return $this->render('document/preview_error.html.twig', ['exception' => $exc]);
        }
    }

    private function renderSection(DocumentBuilder $builder, Document $document, $section)
    {
        return $builder->build($document, $section);
    }

    private function processForm(Request $request, DocumentTemplateFormProcessor $processor, DocumentTemplate $documentTemplate = null)
    {
        $processor->process($request, $documentTemplate);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ?
                'flash.document_template.created' :
                'flash.document_template.updated',
                ['%document_template%' => $processor->getData()->getName()]);

            if ($processor->isRedirectingTo(DocumentTemplateFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('document_templates');
            }

            return $this->redirectToRoute('document_template_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }

    protected function createDocumentTemplatePerCompany(Company $company, DocumentTemplate $documentTemplate)
    {
        $documentTemplatePerCompany = new DocumentTemplatePerCompany();
        $documentTemplatePerCompany->setCompany($company);
        $documentTemplatePerCompany->setDocumentTemplate($documentTemplate);
        $documentTemplatePerCompany->copyDocumentTemplateDetails();

        return $documentTemplatePerCompany;
    }
}
