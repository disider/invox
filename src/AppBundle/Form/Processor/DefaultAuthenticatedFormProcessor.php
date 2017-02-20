<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Processor;

use AppBundle\Entity\Repository\AbstractRepository;
use AppBundle\Form\AccountForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultAuthenticatedFormProcessor extends AbstractEntityFormProcessor
{
    private $formClass;

    public function __construct($formClass, AbstractRepository $repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($repository, $formFactory, $tokenStorage);

        $this->formClass = $formClass;
    }

    protected function getFormClass()
    {
        return $this->formClass;
    }

    protected function evaluateRedirect()
    {
        $this->setRedirectTo(
            $this->isButtonClicked(
                'saveAndClose'
            ) ? self::REDIRECT_TO_LIST : null
        );
    }

    protected function getFormOptions()
    {
        return ['user' => $this->getAuthenticatedUser()];
    }
}
