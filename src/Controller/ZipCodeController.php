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

use App\Entity\ZipCode;
use App\Form\Processor\ZipCodeFormProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/zip-codes")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class ZipCodeController extends BaseController
{
    /**
     * @Route("", name="zip_codes")
     * @Security("is_granted('LIST_ZIP_CODES')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $this->getZipCodeRepository()->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize, 'zipCode.code', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="zip_code_create")
     * @Security("is_granted('ZIP_CODE_CREATE')")
     * @Template
     */
    public function createAction(Request $request, ZipCodeFormProcessor $processor)
    {
        $zipCode = new ZipCode();

        return $this->processForm($request, $processor, $zipCode);
    }

    /**
     * @Route("/{id}/edit", name="zip_code_edit")
     * @Security("is_granted('ZIP_CODE_EDIT', zipCode)")
     * @Template
     */
    public function editAction(Request $request, ZipCodeFormProcessor $processor, ZipCode $zipCode)
    {
        return $this->processForm($request, $processor, $zipCode);
    }

    /**
     * @Route("/{id}/delete", name="zip_code_delete")
     * @Security("is_granted('ZIP_CODE_DELETE', zipCode)")
     */
    public function deleteAction(ZipCode $zipCode)
    {
        $this->delete($zipCode);

        $this->addFlash('success', 'flash.zip_code.deleted', ['%zip_code%' => $zipCode]);

        return $this->redirectToRoute('zip_codes');
    }

    /**
     * @Route("/search", name="zip_code_search")
     */
    public function searchAction(Request $request)
    {
        $term = $request->get('term');

        $zipCodes = $this->getZipCodeRepository()->search($term);

        return $this->serialize([
            'zipCodes' => $zipCodes,
        ]);
    }

    private function processForm(Request $request, ZipCodeFormProcessor $processor, ZipCode $zipCode)
    {
        $processor->process($request, $zipCode);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.zip_code.created' : 'flash.zip_code.updated',
                ['%zip_code%' => $processor->getData()]);

            if ($processor->isRedirectingTo(ZipCodeFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('zip_codes');
            }

            return $this->redirectToRoute('zip_code_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
