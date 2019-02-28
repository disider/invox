<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Processor;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Repository\DocumentRepository;
use AppBundle\Form\DocumentForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class WorkingNoteFormProcessor extends AbstractFormProcessor
{
    /**
     * @var 
     */
    private $workingNoteRepository;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /** @var Document */
    private $document;

    /**
     * @var array
     */
    private $availableLocales;

    public function __construct(DocumentRepository $documentRepository, CustomerRepository $customerRepository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage, array $availableLocales)
    {
        parent::__construct($formFactory, $tokenStorage);

        $this->workingNoteRepository = $documentRepository;
        $this->customerRepository = $customerRepository;
        $this->availableLocales = $availableLocales;
    }

    protected function getFormClass()
    {
        return new DocumentForm($this->customerRepository, $this->availableLocales);
    }

    public function getDocument()
    {
        return $this->document;
    }

    protected function evaluateRedirect()
    {
        $this->setRedirectTo(
            $this->isButtonClicked(
                'saveAndClose'
            ) ? self::REDIRECT_TO_LIST : null
        );
    }

    protected function handleRequest(Request $request)
    {
        if ($request->isMethod('POST')) {
            $form = $this->getForm();

            $form->handleRequest($request);

            if ($this->isValid()) {
                if ($this->isSaving()) {
                    $this->document = $form->getData();

                    $this->workingNoteRepository->save($this->document);

                    $this->evaluateRedirect();
                } else if ($this->isButtonClicked('linkCustomer')) {
                    $this->document = $form->getData();
                    $this->document->copyCustomerDetails();
                }
            }
        }
    }

    public function isSaving()
    {
        return $this->isButtonClicked('save') || $this->isButtonClicked('saveAndClose');
    }

    /**
     * @param Document $document
     */
    protected function onPreSave($document)
    {
        $customer = $this->customerRepository->findOneByCode($document->getCustomerCode());

        if (!$customer) {
            $customer = Customer::create(
                $document->getCompany(),
                $document->getCustomerName(),
                '',
                $document->getCustomerVatNumber(),
                $document->getCustomerFiscalCode(),
                $document->getCustomerCountry(),
                $document->getCustomerProvince(),
                $document->getCustomerCity(),
                $document->getCustomerZipCode(),
                $document->getCustomerAddress(),
                $document->getCustomerAddressNotes()
            );

            $this->customerRepository->save($customer);
        }

        $document->setLinkedCustomer($customer);

        foreach ($document->getRows() as $row) {
            $row->setDocument($document);
            $this->getRepository()->save($row);
        }
    }

    protected function getFormOptions()
    {
        return [];
    }
}
