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

use AppBundle\Entity\Page;
use AppBundle\Form\Processor\DefaultFormProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 */
class PageController extends BaseController
{
    /**
     * @Route("/pages", name="pages")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $this->getPageRepository()->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/content/{url}", name="page_view")
     * @Template
     */
    public function viewAction(Request $request, $url)
    {
        return [
        ];
    }

    /**
     * @Route("/pages/new", name="page_create")
     * @Template
     */
    public function createAction(Request $request)
    {
        return $this->processForm($request);
    }

    /**
     * @Route("/pages/{id}/edit", name="page_edit")
     * @Template
     */
    public function editAction(Request $request, Page $page)
    {
        return $this->processForm($request, $page);
    }

    /**
     * @Route("/pages/{id}/delete", name="page_delete")
     */
    public function deleteAction(Page $page)
    {
        $this->delete($page);

        $this->addFlash('success', 'flash.page.deleted', ['%page%' => $page]);

        return $this->redirectToRoute('pages');
    }

    private function processForm(Request $request, Page $page = null)
    {
        /** @var DefaultFormProcessor $processor */
        $processor = $this->get('page_form_processor');

        $processor->process($request, $page);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.page.created' : 'flash.page.updated',
                ['%page%' => $processor->getData()]);

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('pages');
            }

            return $this->redirectToRoute('page_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}