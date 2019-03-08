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

use App\Entity\Manager\UserManager;
use App\Form\UserForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserFormProcessor extends AbstractFormProcessor
{
    /**
     * @var UserManager
     */
    private $userManager;

    private $user;

    public function __construct(
        UserManager $userManager,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage
    )
    {
        parent::__construct($formFactory, $tokenStorage);

        $this->userManager = $userManager;
    }

    protected function handleRequest(Request $request)
    {
        if ($request->isMethod('POST')) {
            $form = $this->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->user = $form->getData();

                $this->userManager->updateUser($this->user);

                $this->evaluateRedirect();
            }
        }
    }

    protected function getFormClass()
    {
        return UserForm::class;
    }

    protected function getFormOptions()
    {
        return [
            'user' => $this->getAuthenticatedUser(),
        ];
    }

    public function getUser()
    {
        return $this->user;
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
