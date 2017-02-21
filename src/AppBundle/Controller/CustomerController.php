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

use AppBundle\Entity\Customer;
use AppBundle\Entity\Repository\CustomerRepository;
use AppBundle\Form\Filter\CustomerFilterForm;
use AppBundle\Form\Processor\CustomerFormProcessor;
use AppBundle\Model\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/customers")
 */
class CustomerController extends BaseController
{
    /**
     * @Route("", name="customers")
     * @Security("is_granted('LIST_CUSTOMERS')")
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
            $filters[CustomerRepository::FILTER_BY_MANAGER] = $user;
        }
        $filters[CustomerRepository::FILTER_BY_COMPANY] = $this->getCurrentCompany();

        $query = $this->getCustomerRepository()->findAllQuery($filters, $page, $pageSize);
        $filterForm = $this->buildFilterForm($request, $query, CustomerFilterForm::class);

        $pagination = $this->paginate($query, $page, $pageSize, 'customer.name', 'asc');

        return [
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ];
    }

    /**
     * @Route("/new", name="customer_create")
     * @Security("is_granted('CUSTOMER_CREATE')")
     * @Template
     */
    public function createAction(Request $request)
    {
        $customer = new Customer();
        $customer->setCompany($this->getCurrentCompany());
        $customer->setLanguage(Language::ITALIAN);

        return $this->processForm($request, $customer);
    }

    /**
     * @Route("/{id}/edit", name="customer_edit")
     * @Security("is_granted('CUSTOMER_EDIT', customer)")
     * @Template
     */
    public function editAction(Request $request, Customer $customer)
    {
        return $this->processForm($request, $customer);
    }

    /**
     * @Route("/{id}/delete", name="customer_delete")
     * @Security("is_granted('CUSTOMER_DELETE', customer)")
     */
    public function deleteAction(Customer $customer)
    {
        $this->getCustomerRepository()->delete($customer);

        $this->addFlash('success', 'flash.customer.deleted', ['%customer%' => $customer]);

        return $this->redirectToRoute('customers');
    }

    /**
     * @Route("/search", name="customer_search")
     */
    public function searchAction(Request $request)
    {
        $term = $request->get('term');

        $customers = $this->getCustomerRepository()->search($term, $this->getCurrentCompany());

        return $this->serialize([
            'customers' => $customers,
        ]);
    }

    private function processForm(Request $request, Customer $customer)
    {
        /** @var CustomerFormProcessor $processor */
        $processor = $this->get('customer_form_processor');

        $processor->process($request, $customer);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.customer.created' : 'flash.customer.updated',
                ['%customer%' => $processor->getData()]);

            if ($processor->isRedirectingTo(CustomerFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('customers');
            }

            return $this->redirectToRoute('customer_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
