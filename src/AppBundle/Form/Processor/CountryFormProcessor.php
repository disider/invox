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

use AppBundle\Form\CountryForm;
use AppBundle\Repository\CountryRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CountryFormProcessor extends DefaultFormProcessor
{
    public function __construct(CountryRepository $repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct(CountryForm::class, $repository, $formFactory, $tokenStorage);
    }
}
