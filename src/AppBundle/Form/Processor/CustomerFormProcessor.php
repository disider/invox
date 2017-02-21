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
use AppBundle\Form\CustomerForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CustomerFormProcessor extends AbstractEntityFormProcessor
{
    protected function getFormClass()
    {
        return CustomerForm::class;
    }

    protected function getFormOptions() {
        return [
            'user' => $this->getAuthenticatedUser(),
        ];
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
