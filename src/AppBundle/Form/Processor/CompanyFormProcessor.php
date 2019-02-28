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

use AppBundle\Form\CompanyForm;
use AppBundle\Repository\CompanyRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CompanyFormProcessor extends DefaultAuthenticatedFormProcessor
{
    public function __construct(CompanyRepository $repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct(CompanyForm::class, $repository, $formFactory, $tokenStorage);
    }
}
