<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Tests;

use Mockery as m;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

abstract class FormProcessorTestCase extends WebTestCase
{
    //    /** @var BaseFormProcessor */
//    protected $processor;
//
//    /** @var FormInterface */
//    private $form;
//
//    /** @var SecurityContextInterface */
//    private $securityContext;
//
//    protected abstract function buildProcessor(
//        FormFactoryInterface $formFactory,
//        SecurityContextInterface $securityContext
//    );
//
//    protected abstract function getFormName();
//
//    protected abstract function buildFormData();
//
//    protected function setUp()
//    {
//        $this->form = m::mock('Symfony\Component\Form\Form');
//        $this->form->shouldReceive('handleRequest');
//        $this->form->shouldReceive('setData');
//
//        $button = m::mock('Symfony\Component\Form\SubmitButton');
//        $button->shouldReceive('isClicked');
//        $this->form->shouldReceive('get')->andReturn($button);
//        $this->form->shouldReceive('has');
//
//        $formFactory = m::mock('Symfony\Component\Form\FormFactoryInterface');
//        $formFactory->shouldReceive('create')
//            ->andReturn($this->form);
//
//        $this->securityContext = m::mock('Symfony\Component\Security\Core\SecurityContextInterface');
//
//        $this->processor = $this->buildProcessor(
//            $formFactory,
//            $this->securityContext
//        );
//        $this->formName = $this->getFormName();
//    }
//
//    protected function givenValidData($entity = null)
//    {
//        $this->givenValidForm($entity);
//
//        return $this->givenPostRequest($entity);
//    }
//
//    protected function givenInvalidData()
//    {
//        $this->form
//            ->shouldReceive('isValid')
//            ->once()
//            ->andReturn(false);
//
//        return $this->givenPostRequest(array());
//    }
//
//    protected function givenPostRequest($data = array())
//    {
//        $request = new Request(array(), array($this->formName => $data));
//        $request->setMethod('POST');
//
//        return $request;
//    }
//
//    protected function givenValidForm($data)
//    {
//        $this->form
//            ->shouldReceive('isValid')
//            ->once()
//            ->andReturn(true);
//
//        $this->form
//            ->shouldReceive('getData')
//            ->once()
//            ->andReturn($data);
//    }
//
//    protected function givenNotAuthorized()
//    {
//        $this->securityContext
//            ->shouldReceive('isGranted')
//            ->andReturn(false);
//    }
//
//    protected function expectInteractorFor($interactor, $name)
//    {
//        $expect = $this->interactorFactory->shouldReceive('get')
//            ->with($name)
//            ->andReturn($interactor)
//            ->once();
//
//        return $expect;
//    }
//
//    protected function givenErrorInteractorFor($name)
//    {
//        $interactor = new ErrorInteractor('Error');
//
//        $this->interactorFactory->shouldReceive('get')
//            ->with($name)
//            ->andReturn($interactor);
//    }
//
//    protected function givenAuthorized()
//    {
//        $this->securityContext
//            ->shouldReceive('isGranted')
//            ->andReturn(true);
//    }
//
//    protected function givenLoggedUser($role = User::ROLE_USER)
//    {
//        $user = $this->givenUser($role);
//
//        $this->givenAuthorized();
//
//        $token = new DummyToken($user);
//
//        $this->securityContext
//            ->shouldReceive('getToken')
//            ->once()
//            ->andReturn($token);
//
//        return $user;
//    }
//
//    protected function givenLoggedSuperadmin()
//    {
//        $this->givenLoggedUser(User::ROLE_SUPER_ADMIN);
//    }
//
//    protected function expectFormDataIsSet()
//    {
//        $expect = $this->form->mockery_findExpectation('setData', array());
//        $expect->once();
//
//        return $expect;
//    }
//
//    protected function givenUser($role = User::ROLE_USER)
//    {
//        $user = new User(1, 'adam@example.com', 'password', 'salt');
//        $user->addRole($role);
//
//        return $user;
//    }
//
//    protected function givenAdmin()
//    {
//        $user = new User(2, 'admin@example.com', 'password', 'salt');
//        $user->addRole(User::ROLE_ADMIN);
//
//        return $user;
//    }
//
//    /**
//     * @return m\MockInterface
//     */
//    protected function buildEntityFactory()
//    {
//        $entity = $this->buildFormData();
//        $entityFactory = m::mock('Diside\SecurityBundle\Factory\EntityFactory');
//        $entityFactory->shouldReceive('create')
//            ->andReturn($entity);
//        $entityFactory->shouldReceive('getClass')
//            ->andReturn(get_class($entity));
//
//        return $entityFactory;
//    }
//
//    /**
//     * @return m\MockInterface
//     */
//    protected function buildRequestFactory()
//    {
//        $dummyRequest = new DummyRequest();
//
//        $requestFactory = m::mock('Diside\SecurityBundle\Factory\RequestFactory');
//        $requestFactory->shouldReceive('create')
//            ->andReturn($dummyRequest);
//
//        return $requestFactory;
//    }
}
