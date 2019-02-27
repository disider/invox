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
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

abstract class AbstractEntityFormProcessor extends AbstractFormProcessor
{
    /** @var AbstractRepository */
    protected $repository;

    private $data;

    public function __construct($repository, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($formFactory, $tokenStorage);

        $this->repository = $repository;
    }

    protected function handleRequest(Request $request)
    {
        if ($request->isMethod('POST')) {
            /** @var Form $form */
            $form = $this->getForm();

            $form->handleRequest($request);

            if($this->isValid()) {
                $this->data = $form->getData();

                $this->repository->save($this->data);

                $this->evaluateRedirect();
            }
        }
    }

    protected function getRepository()
    {
        return $this->repository;
    }

    public function getData()
    {
        return $this->data;
    }
}
