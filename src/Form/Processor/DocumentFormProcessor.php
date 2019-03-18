<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Processor;

use App\Entity\Document;
use App\Entity\Manager\CompanyManager;
use App\Form\DocumentForm;
use App\Repository\DocumentRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DocumentFormProcessor extends AbstractFormProcessor
{
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /** @var Document */
    private $document;

    /**
     * @var array
     */
    private $availableLocales;
    /**
     * @var CompanyManager
     */
    private $companyManager;

    public function __construct(
        DocumentRepository $inviteRepository,
        CompanyManager $companyManager,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage,
        array $availableLocales
    ) {
        parent::__construct($formFactory, $tokenStorage);

        $this->documentRepository = $inviteRepository;
        $this->availableLocales = $availableLocales;
        $this->companyManager = $companyManager;
    }

    protected function getFormClass()
    {
        return DocumentForm::class;
    }

    protected function getFormOptions()
    {
        return [
            'company' => $this->getCurrentCompany(),
            'available_locales' => $this->availableLocales,
        ];
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
                    if ($this->document->isAddNewCustomer()) {
                        $customer = $this->document->buildCustomer();

                        $this->document->setLinkedCustomer($customer);
                    }

                    $this->documentRepository->save($this->document);

                    $this->evaluateRedirect();
                }
            }
        }
    }

    public function isSaving()
    {
        return $this->isButtonClicked('save') || $this->isButtonClicked('saveAndClose');
    }

    public function getCurrentCompany()
    {
        return $this->companyManager->getCurrent();
    }
}
