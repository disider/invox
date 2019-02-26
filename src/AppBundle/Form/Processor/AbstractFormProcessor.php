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

use AppBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class AbstractFormProcessor
{
    const REDIRECT_TO_LIST = 'redirect_to_list';

    /** @var Form */
    private $form;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var string */
    private $redirectTo;

    /** @var bool */
    private $isNew;

    public function __construct(FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->formFactory = $formFactory;
    }

    abstract protected function getFormClass();
    abstract protected function getFormOptions();

    abstract protected function handleRequest(Request $request);

    public function getForm()
    {
        return $this->form;
    }

    public function process(Request $request, $object = null)
    {
        $this->isNew = (!$object || ($object->getId() == null));

        $this->form = $this->formFactory->create($this->getFormClass(), $object, $this->getFormOptions());

        $this->handleRequest($request);
    }

    public function isValid()
    {
        return $this->getForm()->isSubmitted() && $this->getForm()->isValid();
    }

    public function isNew()
    {
        return $this->isNew;
    }

    public function isRedirectingTo($to)
    {
        return $this->redirectTo == $to;
    }

    /** @return User */
    protected function getAuthenticatedUser()
    {
        $token = $this->tokenStorage->getToken();

        /** @var User $user */
        $user = $token->getUser();

        return $user;
    }

    protected function evaluateRedirect()
    {
    }

    protected function isUserAuthenticated()
    {
        return $this->tokenStorage->isGranted('IS_AUTHENTICATED_REMEMBERED');
    }

    protected function setRedirectTo($to)
    {
        $this->redirectTo = $to;
    }

    protected function isButtonClicked($buttonName)
    {
        if (!$this->form->has($buttonName)) {
            return false;
        }

        return $this->form->get($buttonName)->isClicked();
    }

    protected function getFormData()
    {
        return $this->form->getData();
    }

    /** @return TokenStorageInterface */
    public function getTokenStorage()
    {
        return $this->tokenStorage;
    }
}
