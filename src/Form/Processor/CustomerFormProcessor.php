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

use App\Form\CustomerForm;
use App\Repository\CustomerRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CustomerFormProcessor extends DefaultAuthenticatedFormProcessor
{
    private $router;

    public function __construct(
        CustomerRepository $repository,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router
    ) {
        parent::__construct(CustomerForm::class, $repository, $formFactory, $tokenStorage);
        $this->router = $router;
    }

    protected function getFormOptions()
    {
        return [
            'search_url' => $this->router->generate('zip_code_search'),
            'user' => $this->getAuthenticatedUser(),
        ];
    }
}
