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

use App\Form\RecurrenceForm;
use App\Repository\RecurrenceRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RecurrenceFormProcessor extends DefaultFormProcessor
{
    public function __construct(
        RecurrenceRepository $repository,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct(RecurrenceForm::class, $repository, $formFactory, $tokenStorage);
    }
}
