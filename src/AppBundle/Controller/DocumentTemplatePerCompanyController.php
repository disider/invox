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

use AppBundle\Entity\Company;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentTemplatePerCompany;
use AppBundle\Form\Processor\DefaultFormProcessor;
use AppBundle\Form\Processor\DocumentTemplatePerCompanyFormProcessor;
use AppBundle\Repository\DocumentTemplatePerCompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/companies/{companyId}/document-templates")
 */
class DocumentTemplatePerCompanyController extends BaseController
{
    /**
     * @Route("", name="document_templates_per_company")
     * @Security("is_granted('LIST_DOCUMENT_TEMPLATES_PER_COMPANY', company)")
     * @ParamConverter("company", class="AppBundle:Company", options={"id" = "companyId"})
     * @Template
     */
    public function indexAction(Request $request, Company $company)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $filters = [
            DocumentTemplatePerCompanyRepository::FILTER_BY_COMPANY => $this->getCurrentCompany()
        ];

        $query = $this->getDocumentTemplatePerCompanyRepository()->findAllQuery($filters, $page, $pageSize);

        $pagination = $this->paginate($query, $page, $pageSize);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/{id}/edit", name="document_template_per_company_edit")
     * @Security("is_granted('DOCUMENT_TEMPLATE_PER_COMPANY_EDIT', documentTemplate)")
     * @ParamConverter("company", class="AppBundle:Company", options={"id" = "companyId"})
     * @ParamConverter("documentTemplate", class="AppBundle:DocumentTemplatePerCompany", options={"id" = "id"})
     * @Template
     */
    public function editAction(Request $request, DocumentTemplatePerCompanyFormProcessor $processor, DocumentTemplatePerCompany $documentTemplate)
    {
        return $this->processForm($request, $processor, $documentTemplate);
    }

    /**
     * @Route("/{id}/restore", name="document_template_per_company_restore")
     * @Security("is_granted('DOCUMENT_TEMPLATE_PER_COMPANY_EDIT', documentTemplate)")
     * @ParamConverter("company", class="AppBundle:Company", options={"id" = "companyId"})
     * @ParamConverter("documentTemplate", class="AppBundle:DocumentTemplatePerCompany", options={"id" = "id"})
     */
    public function restoreAction(DocumentTemplatePerCompany $documentTemplate)
    {
        $documentTemplate->copyDocumentTemplateDetails();
        $this->save($documentTemplate);

        $this->addFlash('success', 'flash.document_template.restored', [
            '%document_template%' => $documentTemplate->getName()
        ]);

        return $this->redirectToRoute('document_template_per_company_edit', [
            'companyId' => $documentTemplate->getCompany()->getId(),
            'id' => $documentTemplate->getId(),
        ]);
    }

    /**
     * @Route("/{id}/preview", name="document_template_per_company_preview")
     * @Security("is_granted('DOCUMENT_TEMPLATE_PER_COMPANY_PREVIEW', documentTemplate)")
     * @ParamConverter("company", class="AppBundle:Company", options={"id" = "companyId"})
     * @ParamConverter("documentTemplate", class="AppBundle:DocumentTemplatePerCompany", options={"id" = "id"})
     * @Template
     */
    public function previewAction(DocumentTemplatePerCompany $documentTemplate)
    {
        return [
            'documentTemplate' => $documentTemplate
        ];
    }

    /**
     * @Route("/{id}/render", name="document_template_per_company_render")
     * @Security("is_granted('DOCUMENT_TEMPLATE_PER_COMPANY_RENDER', documentTemplate)")
     * @ParamConverter("company", class="AppBundle:Company", options={"id" = "companyId"})
     * @ParamConverter("documentTemplate", class="AppBundle:DocumentTemplatePerCompany", options={"id" = "id"})
     */
    public function renderAction(Request $request, DocumentTemplatePerCompany $documentTemplate)
    {
        $locale = $this->getCurrentLocale();
        $request->setLocale($locale);
        $this->get('translator')->setLocale($locale);

        $this->disableProfiler();

        $company = $this->getCurrentCompany();
        $document = Document::createForTestingTemplates($company, $documentTemplate, $locale);

        try {
            $params = [
                'template' => $documentTemplate,
                'header' => $this->renderSection($document, 'header'),
                'footer' => $this->renderSection($document, 'footer'),
                'content' => $this->renderSection($document, 'content')
            ];

            return $this->render('AppBundle:document_template_per_company:render.html.twig', $params);
        } catch (\Exception $exc) {
            return $this->render('AppBundle:document:preview_error.html.twig', ['exception' => $exc]);
        }
    }

    private function renderSection(Document $document, $section)
    {
        $documentBuilder = $this->get('document_builder');

        return $documentBuilder->build($document, $section);
    }

    private function processForm(Request $request, DocumentTemplatePerCompanyFormProcessor $processor, DocumentTemplatePerCompany $documentTemplate = null)
    {
        $processor->process($request, $documentTemplate);

        if ($processor->isValid()) {
            /** @var DocumentTemplatePerCompany $template */
            $template = $processor->getData();
            $this->addFlash('success', 'flash.document_template.updated', [
                '%document_template%' => $template->getName()
            ]);

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('document_templates_per_company', [
                    'companyId' => $this->getCurrentCompany()->getId(),
                ]);
            }

            return $this->redirectToRoute('document_template_per_company_edit', [
                'companyId' => $template->getCompany()->getId(),
                'id' => $template->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
