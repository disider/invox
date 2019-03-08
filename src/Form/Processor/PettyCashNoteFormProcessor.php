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

use App\Form\PettyCashNoteForm;
use App\Repository\PettyCashNoteRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PettyCashNoteFormProcessor extends DefaultAuthenticatedFormProcessor
{
    public function __construct(PettyCashNoteRepository $repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct(PettyCashNoteForm::class, $repository, $formFactory, $tokenStorage);
    }

    public function isValid()
    {
        if ($this->isButtonClicked('save') || $this->isButtonClicked('saveAndClose')) {
            return parent::isValid();
        }

        return false;
    }
}
