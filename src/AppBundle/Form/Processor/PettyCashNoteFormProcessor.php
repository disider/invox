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

use AppBundle\Repository\DocumentRepository;
use AppBundle\Entity\Repository\PettyCashNoteRepository;
use AppBundle\Form\PettyCashNoteForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PettyCashNoteFormProcessor extends AbstractEntityFormProcessor
{
    protected function getFormClass()
    {
        return PettyCashNoteForm::class;
    }

    protected function evaluateRedirect()
    {
        $this->setRedirectTo(
            $this->isButtonClicked(
                'saveAndClose'
            ) ? self::REDIRECT_TO_LIST : null
        );
    }

    public function isValid()
    {
        if ($this->isButtonClicked('save') || $this->isButtonClicked('saveAndClose')) {
            return parent::isValid();
        }

        return false;
    }

    protected function getFormOptions()
    {
        return [
            'user' => $this->getAuthenticatedUser(),
        ];
    }
}
