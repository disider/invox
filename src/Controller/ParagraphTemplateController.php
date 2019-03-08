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

use App\Entity\ParagraphTemplate;
use App\Form\Filter\ParagraphTemplateFilterForm;
use App\Form\Processor\ParagraphTemplateFormProcessor;
use App\Repository\ParagraphTemplateRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paragraph-templates")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class ParagraphTemplateController extends BaseController
{

    /**
     * @Route("", name="paragraph_templates")
     * @Security("is_granted('LIST_PARAGRAPH_TEMPLATES')")
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
            $filters[ParagraphTemplateRepository::FILTER_BY_MANAGER] = $user;
            $filters[ParagraphTemplateRepository::FILTER_BY_SALES_AGENT] = $user;
        }

        $query = $this->getParagraphTemplateRepository()->findAllQuery($filters, $page, $pageSize);

        $filterForm = $this->buildFilterForm($request, $query, ParagraphTemplateFilterForm::class);

        $pagination = $this->paginate($query, $page, $pageSize);

        return [
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/browse", name="paragraph_templates_browse")
     * @Template
     */
    public function browseAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $filters = [];

        $user = $this->getUser();

        if ($user->isSuperadmin()) {
            // Do not filter...
        } else {
            $filters[ParagraphTemplateRepository::FILTER_BY_MANAGER] = $user;
            $filters[ParagraphTemplateRepository::FILTER_BY_SALES_AGENT] = $user;
        }

        $query = $this->getParagraphTemplateRepository()->findAllQuery($filters);

        $pagination = $this->paginate($query, $page, $pageSize, 'paragraphTemplate.title', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="paragraph_template_create")
     * @Security("is_granted('PARAGRAPH_TEMPLATE_CREATE')")
     * @Template
     */
    public function createAction(Request $request, ParagraphTemplateFormProcessor $processor)
    {
        $paragraphTemplate = new ParagraphTemplate();

        $company = $this->getCurrentCompany();

        if ($company) {
            $paragraphTemplate->setCompany($company);
        }

        return $this->processForm($request, $processor, $paragraphTemplate);
    }

    /**
     * @Route("/{id}/show", name="paragraph_template_show")
     */
    public function showAction(ParagraphTemplate $paragraphTemplate)
    {
        return $this->serialize($paragraphTemplate);
    }

    /**
     * @Route("/{id}/edit", name="paragraph_template_edit")
     * @Security("is_granted('PARAGRAPH_TEMPLATE_EDIT', paragraphTemplate)")
     * @Template
     */
    public function editAction(Request $request, ParagraphTemplateFormProcessor $processor, ParagraphTemplate $paragraphTemplate)
    {
        return $this->processForm($request, $processor, $paragraphTemplate);
    }

    /**
     * @Route("/{id}/delete", name="paragraph_template_delete")
     * @Security("is_granted('PARAGRAPH_TEMPLATE_DELETE', paragraphTemplate)")
     */
    public function deleteAction(ParagraphTemplate $paragraphTemplate)
    {
        $this->getParagraphTemplateRepository()->delete($paragraphTemplate);
        $this->addFlash('success', 'flash.paragraph_template.deleted');

        return $this->redirectToRoute('paragraph_templates');
    }

    protected function processForm(Request $request, ParagraphTemplateFormProcessor $processor, ParagraphTemplate $paragraphTemplate)
    {
        $processor->process($request, $paragraphTemplate);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.paragraph_template.created' : 'flash.paragraph_template.updated');

            if ($processor->isRedirectingTo(ParagraphTemplateFormProcessor::REDIRECT_TO_LIST))
                return $this->redirectToRoute('paragraph_templates');

            return $this->redirectToRoute('paragraph_template_edit', ['id' => $processor->getData()->getId()]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView()
        ];
    }

}
