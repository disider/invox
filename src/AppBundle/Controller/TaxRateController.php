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

use AppBundle\Entity\TaxRate;
use AppBundle\Form\Processor\TaxRateFormProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tax-rates")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class TaxRateController extends BaseController
{
    /**
     * @Route("", name="tax_rates")
     * @Security("is_granted('LIST_TAX_RATES')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $this->getTaxRateRepository()->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize, 'entity.amount', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="tax_rate_create")
     * @Security("is_granted('TAX_RATE_CREATE')")
     * @Template
     */
    public function createAction(Request $request, TaxRateFormProcessor $processor)
    {
        $position = $this->getTaxRateRepository()->countAll();

        $taxRate = TaxRate::createEmpty($position);

        return $this->processForm($request, $processor, $taxRate);
    }

    /**
     * @Route("/{id}/edit", name="tax_rate_edit")
     * @Security("is_granted('TAX_RATE_EDIT', taxRate)")
     * @Template
     */
    public function editAction(Request $request, TaxRateFormProcessor $processor, TaxRate $taxRate)
    {
        return $this->processForm($request, $processor, $taxRate);
    }

    /**
     * @Route("/{id}/delete", name="tax_rate_delete")
     * @Security("is_granted('TAX_RATE_DELETE', taxRate)")
     */
    public function deleteAction(TaxRate $taxRate)
    {
        $name = (string)$taxRate;

        $this->delete($taxRate);

        $this->addFlash('success', 'flash.tax_rate.deleted', ['%tax_rate%' => $name]);

        return $this->redirectToRoute('tax_rates');
    }

    private function processForm(Request $request, TaxRateFormProcessor $processor, TaxRate $taxRate)
    {
        $processor->process($request, $taxRate);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.tax_rate.created' : 'flash.tax_rate.updated',
                ['%tax_rate%' => $processor->getData()]);

            if ($processor->isRedirectingTo(TaxRateFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('tax_rates');
            }

            return $this->redirectToRoute('tax_rate_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
