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

use AppBundle\Entity\Province;
use AppBundle\Form\Processor\DefaultFormProcessor;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/provinces")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class ProvinceController extends BaseController
{
    /**
     * @Route("", name="provinces")
     * @Security("is_granted('LIST_PROVINCES')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $this->getProvinceRepository()->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize, 'entity.name', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="province_create")
     * @Security("is_granted('PROVINCE_CREATE')")
     * @Template
     */
    public function createAction(Request $request)
    {
        $province = new Province();

        return $this->processForm($request, $province);
    }

    /**
     * @Route("/{id}/edit", name="province_edit")
     * @Security("is_granted('PROVINCE_EDIT', province)")
     * @Template
     */
    public function editAction(Request $request, Province $province)
    {
        return $this->processForm($request, $province);
    }

    /**
     * @Route("/{id}/delete", name="province_delete")
     * @Security("is_granted('PROVINCE_DELETE', province)")
     */
    public function deleteAction(Province $province)
    {
        $this->delete($province);

        $this->addFlash('success', 'flash.province.deleted', ['%province%' => $province]);

        return $this->redirectToRoute('provinces');
    }

    private function processForm(Request $request, Province $province = null)
    {
        /** @var DefaultFormProcessor $processor */
        $processor = $this->get('province_form_processor');

        $processor->process($request, $province);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.province.created' : 'flash.province.updated',
                ['%province%' => $processor->getData()]);

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('provinces');
            }

            return $this->redirectToRoute('province_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
