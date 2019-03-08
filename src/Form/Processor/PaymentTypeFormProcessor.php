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

use App\Repository\PaymentTypeRepository;
use App\Form\PaymentTypeForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PaymentTypeFormProcessor extends DefaultFormProcessor
{
    public function __construct(PaymentTypeRepository $repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct(PaymentTypeForm::class, $repository, $formFactory, $tokenStorage);
    }
}
