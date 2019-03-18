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

use App\Form\ZipCodeForm;
use App\Repository\ZipCodeRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ZipCodeFormProcessor extends DefaultFormProcessor
{
    public function __construct(
        ZipCodeRepository $repository,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct(ZipCodeForm::class, $repository, $formFactory, $tokenStorage);
    }
}
