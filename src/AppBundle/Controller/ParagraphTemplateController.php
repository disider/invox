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

use AppBundle\Entity\ParagraphTemplate;
use AppBundle\Entity\Repository\ParagraphTemplateRepository;
use AppBundle\Form\Filter\ParagraphTemplateFilterForm;
use AppBundle\Form\Processor\DefaultFormProcessor;
use JMS\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
    public function createAction(Request $request)
    {
        $paragraphTemplate = new ParagraphTemplate();

        $company = $this->getCurrentCompany();

        if ($company) {
            $paragraphTemplate->setCompany($company);
        }

        return $this->processForm($request, $paragraphTemplate);
    }

    /**
     * @Route("/{id}/show", name="paragraph_template_show")
     */
    public function showAction(ParagraphTemplate $paragraphTemplate)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        return new JsonResponse($serializer->serialize($paragraphTemplate, 'json'));
    }

    /**
     * @Route("/{id}/edit", name="paragraph_template_edit")
     * @Security("is_granted('PARAGRAPH_TEMPLATE_EDIT', paragraphTemplate)")
     * @Template
     */
    public function editAction(Request $request, ParagraphTemplate $paragraphTemplate)
    {
        return $this->processForm($request, $paragraphTemplate);
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

    protected function processForm(Request $request, ParagraphTemplate $paragraphTemplate = null)
    {
        /** @var DefaultFormProcessor $processor */
        $processor = $this->get('paragraph_template_form_processor');

        $processor->process($request, $paragraphTemplate);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.paragraph_template.created' : 'flash.paragraph_template.updated');

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST))
                return $this->redirectToRoute('paragraph_templates');

            return $this->redirectToRoute('paragraph_template_edit', ['id' => $processor->getData()->getId()]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView()
        ];
    }

}
