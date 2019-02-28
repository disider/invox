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

use AppBundle\Form\CityForm;
use AppBundle\Repository\CityRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CityFormProcessor extends DefaultFormProcessor
{
    public function __construct(CityRepository $repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct(CityForm::class, $repository, $formFactory, $tokenStorage);
    }
}
