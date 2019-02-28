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
use AppBundle\Form\Processor\ProvinceFormProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
    public function createAction(Request $request, ProvinceFormProcessor $processor)
    {
        $province = new Province();

        return $this->processForm($request, $processor, $province);
    }

    /**
     * @Route("/{id}/edit", name="province_edit")
     * @Security("is_granted('PROVINCE_EDIT', province)")
     * @Template
     */
    public function editAction(Request $request, ProvinceFormProcessor $processor, Province $province)
    {
        return $this->processForm($request, $processor, $province);
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

    private function processForm(Request $request, ProvinceFormProcessor $processor, Province $province)
    {
        $processor->process($request, $province);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.province.created' : 'flash.province.updated',
                ['%province%' => $processor->getData()]);

            if ($processor->isRedirectingTo(ProvinceFormProcessor::REDIRECT_TO_LIST)) {
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
