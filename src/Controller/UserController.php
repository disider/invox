<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Processor\UserFormProcessor;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UserController extends BaseController
{
    /**
     * @Route("", name="users")
     * @Security("is_granted('LIST_USERS')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $user = $this->getUser();
        $filters = [];

        if ($user->isSuperadmin()) {
            // Do not filter out users
        } elseif ($user->isOwner()) {
            $filters[UserRepository::FILTER_BY_COMPANIES] = $user->getOwnedCompanies();
        }

        $query = $this->getUserRepository()->findAllQuery($filters);

        $pagination = $this->paginate($query, $page, $pageSize, 'user.email', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="user_create")
     * @Security("is_granted('USER_CREATE')")
     * @Template
     */
    public function createAction(Request $request, UserFormProcessor $processor)
    {
        $user = new User();

        if (!$this->getUser()->isSuperadmin()) {
            $company = $this->getCurrentCompany();

            $user->addManagedCompany($company);
        }

        return $this->processForm($request, $processor, $user);
    }

    /**
     * @Route("/{id}/edit", name="user_edit")
     * @Security("is_granted('USER_EDIT', user)")
     * @Template
     *
     * NOTE: cannot use "user" as variable since it's overwritten by Symfony
     */
    public function editAction(Request $request, UserFormProcessor $processor, User $user)
    {
        return $this->processForm($request, $processor, $user);
    }

    /**
     * @Route("/{id}/delete", name="user_delete")
     * @Security("is_granted('USER_DELETE', user)")
     *
     * NOTE: cannot use "user" as variable since it's overwritten by Symfony
     */
    public function deleteAction(User $user)
    {
        if ($user->isSameAs($this->getUser())) {
            throw new \LogicException('Cannot delete myself');
        }

        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'flash.user.deleted', ['%user%' => $user]);

        return $this->redirectToRoute('users');
    }

    private function processForm(Request $request, UserFormProcessor $processor, User $user)
    {
        $processor->process($request, $user);

        if ($processor->isValid()) {
            $this->addFlash(
                'success',
                $processor->isNew() ? 'flash.user.created' : 'flash.user.updated',
                ['%user%' => $processor->getUser()]
            );

            if ($processor->isRedirectingTo(UserFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('users');
            }

            return $this->redirectToRoute(
                'user_edit',
                [
                    'id' => $processor->getUser()->getId(),
                ]
            );
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
