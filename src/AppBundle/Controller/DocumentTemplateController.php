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

use AppBundle\Entity\Company;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentRow;
use AppBundle\Entity\DocumentTemplate;
use AppBundle\Entity\DocumentTemplatePerCompany;
use AppBundle\Entity\Page;
use AppBundle\Entity\TaxRate;
use AppBundle\Form\Processor\DefaultFormProcessor;
use AppBundle\Model\DocumentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $this->getDocumentTemplateRepository()->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize, 'entity.name', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="document_template_create")
     * @Template
     */
    public function createAction(Request $request)
    {
        return $this->processForm($request);
    }

    /**
     * @Route("/{id}/edit", name="document_template_edit")
     * @Template
     */
    public function editAction(Request $request, DocumentTemplate $documentTemplate)
    {
        return $this->processForm($request, $documentTemplate);
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
        foreach($templates as $template) {
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
     * @Template("AppBundle:DocumentTemplatePerCompany:render.html.twig")
     */
    public function renderAction(Request $request, DocumentTemplate $documentTemplate)
    {
        $locale = $this->getCurrentLocale();
        $request->setLocale($locale);
        $this->get('translator')->setLocale($locale);

        $this->disableProfiler();
        $company = $this->getCurrentCompany();

        $documentTemplatePerCompany = $this->createDocumentTemplatePerCompany($company, $documentTemplate);

        $document = Document::createForTestingTemplates($company, $documentTemplatePerCompany, $locale);

        try {
            $params = [
                'template' => $documentTemplatePerCompany,
                'header' => $this->renderSection($document, 'header'),
                'footer' => $this->renderSection($document, 'footer'),
                'content' => $this->renderSection($document, 'content')
            ];

            return $this->render('AppBundle:DocumentTemplatePerCompany:render.html.twig', $params);
        }
        catch(\Exception $exc) {
            return $this->render('AppBundle:Document:previewError.html.twig', ['exception' => $exc]);
        }
    }

    private function renderSection(Document $document, $section)
    {
        $documentBuilder = $this->get('document_builder');

        return $documentBuilder->build($document, $section);
    }


    private function processForm(Request $request, DocumentTemplate $documentTemplate = null)
    {
        /** @var DefaultFormProcessor $processor */
        $processor = $this->get('document_template_form_processor');

        $processor->process($request, $documentTemplate);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ?
                'flash.document_template.created' :
                'flash.document_template.updated',
                ['%document_template%' => $processor->getData()->getName()]);

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST)) {
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
