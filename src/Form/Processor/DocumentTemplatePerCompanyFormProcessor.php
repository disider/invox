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

use App\Form\DocumentTemplatePerCompanyForm;
use App\Repository\DocumentTemplatePerCompanyRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DocumentTemplatePerCompanyFormProcessor extends DefaultAuthenticatedFormProcessor
{
    public function __construct(
        DocumentTemplatePerCompanyRepository $repository,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct(DocumentTemplatePerCompanyForm::class, $repository, $formFactory, $tokenStorage);
    }
}
