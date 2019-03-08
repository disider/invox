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

use App\Repository\AbstractRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultFormProcessor extends AbstractEntityFormProcessor
{
    private $formClass;

    public function __construct($formClass, AbstractRepositoryInterface $repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($repository, $formFactory, $tokenStorage);

        $this->formClass = $formClass;
    }

    protected function getFormClass()
    {
        return $this->formClass;
    }

    protected function getFormOptions()
    {
        return [];
    }

    protected function evaluateRedirect()
    {
        $this->setRedirectTo(
            $this->isButtonClicked(
                'saveAndClose'
            ) ? self::REDIRECT_TO_LIST : null
        );
    }
}
