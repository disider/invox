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

use AppBundle\Entity\PaymentType;
use AppBundle\Form\Processor\DefaultFormProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/payment-types")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class PaymentTypeController extends BaseController
{
    /**
     * @Route("", name="payment_types")
     * @Security("is_granted('LIST_PAYMENT_TYPES')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $this->getPaymentTypeRepository()->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize, 'entity.position', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="payment_type_create")
     * @Security("is_granted('PAYMENT_TYPE_CREATE')")
     * @Template
     */
    public function createAction(Request $request)
    {
        $position = $this->getPaymentTypeRepository()->countAll();

        $paymentType = PaymentType::createEmpty($position);

        return $this->processForm($request, $paymentType);
    }

    /**
     * @Route("/{id}/edit", name="payment_type_edit")
     * @Security("is_granted('PAYMENT_TYPE_EDIT', paymentType)")
     * @Template
     */
    public function editAction(Request $request, PaymentType $paymentType)
    {
        return $this->processForm($request, $paymentType);
    }

    /**
     * @Route("/{id}/delete", name="payment_type_delete")
     * @Security("is_granted('PAYMENT_TYPE_DELETE', paymentType)")
     */
    public function deleteAction(PaymentType $paymentType)
    {
        $name = (string)$paymentType;

        $this->delete($paymentType);

        $this->addFlash('success', 'flash.payment_type.deleted', ['%payment_type%' => $name]);

        return $this->redirectToRoute('payment_types');
    }

    private function processForm(Request $request, PaymentType $paymentType)
    {
        /** @var DefaultFormProcessor $processor */
        $processor = $this->get('payment_type_form_processor');

        $processor->process($request, $paymentType);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.payment_type.created' : 'flash.payment_type.updated',
                ['%payment_type%' => $processor->getData()]);

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('payment_types');
            }

            return $this->redirectToRoute('payment_type_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
