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

use AppBundle\Entity\Repository\AbstractRepository;
use AppBundle\Form\AccountForm;
use AppBundle\Repository\AccountRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccountFormProcessor extends DefaultAuthenticatedFormProcessor
{
    /** @var AbstractRepository */

    public function __construct(AccountRepository $repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct(AccountForm::class, $repository, $formFactory, $tokenStorage);
    }
}
