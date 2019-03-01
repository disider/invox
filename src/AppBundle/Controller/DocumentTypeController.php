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

use AppBundle\Form\Filter\DocumentFilterForm;
use AppBundle\Model\DocumentType;
use AppBundle\Repository\DocumentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DocumentTypeController extends BaseController
{
    /**
     * @Route("/quotes", name="quotes")
     * @Security("is_granted('LIST_QUOTES')")
     * @Template
     */
    public function quotesAction(Request $request)
    {
        return $this->processDocumentType($request, DocumentType::QUOTE);
    }

    /**
     * @Route("/invoices", name="invoices")
     * @Security("is_granted('LIST_INVOICES')")
     * @Template
     */
    public function invoicesAction(Request $request)
    {
        return $this->processDocumentType($request, DocumentType::INVOICE);
    }

    /**
     * @Route("/orders", name="orders")
     * @Security("is_granted('LIST_ORDERS')")
     * @Template
     */
    public function ordersAction(Request $request)
    {
        return $this->processDocumentType($request, DocumentType::ORDER);
    }

    /**
     * @Route("/credit-notes", name="credit_notes")
     * @Security("is_granted('LIST_CREDIT_NOTES')")
     * @Template
     */
    public function creditNotesAction(Request $request)
    {
        return $this->processDocumentType($request, DocumentType::CREDIT_NOTE);
    }

    /**
     * @Route("/delivery-notes", name="delivery_notes")
     * @Security("is_granted('LIST_DELIVERY_NOTES')")
     * @Template
     */
    public function deliveryNotesAction(Request $request)
    {
        return $this->processDocumentType($request, DocumentType::DELIVERY_NOTE);
    }

    /**
     * @Route("/receipts", name="receipts")
     * @Security("is_granted('LIST_RECEIPTS')")
     * @Template
     */
    public function receiptsAction(Request $request)
    {
        return $this->processDocumentType($request, DocumentType::RECEIPT);
    }

    public function processDocumentType(Request $request, $type)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $filters = [];

        $user = $this->getUser();

        if ($user->isSuperadmin()) {
            // Do not filter...
        } elseif ($this->isCurrentAccountant()) {
            $filters[DocumentRepository::FILTER_BY_ACCOUNTANT] = $user;
        } else {
            $filters[DocumentRepository::FILTER_BY_MANAGER] = $user;
        }

        $filters[DocumentRepository::FILTER_BY_COMPANY] = $this->getCurrentCompany();
        $filters[DocumentRepository::FILTER_BY_TYPE] = $type;
        $query = $this->getDocumentRepository()->findAllQuery($filters, $page, $pageSize);

        $filterForm = $this->buildFilterForm($request, $query, DocumentFilterForm::class, ['type' => $type]);

        $pagination = $this->paginate($query, $page, $pageSize, 'document.issuedAt', 'desc');

        $grossTotal = $this->getDocumentRepository()->getGrossTotal($query);

        $result = [
            'grossTotal' => $grossTotal,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
        ];

        if ($type == DocumentType::INVOICE || $type == DocumentType::ORDER || $type == DocumentType::RECEIPT) {
            $result['paidTotal'] = $this->getDocumentRepository()->getPaidTotal($query);
        }

        return $result;
    }

}
