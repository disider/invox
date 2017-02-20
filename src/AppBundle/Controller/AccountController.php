<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\Repository\AccountRepository;
use AppBundle\Form\Processor\DefaultAuthenticatedFormProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/accounts")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class AccountController extends BaseController
{
    /**
     * @Route("", name="accounts")
     * @Security("is_granted('LIST_ACCOUNTS')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $filters = [];

        $user = $this->getUser();

        if ($user->isSuperadmin()) {
            // Do not filter...
        }
        else {
            $filters[AccountRepository::FILTER_BY_MANAGER] = $user;
        }

        $query = $this->getAccountRepository()->findAllQuery($filters, $page, $pageSize);

        $pagination = $this->paginate($query, $page, $pageSize, 'account.name', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="account_create")
     * @Security("is_granted('ACCOUNT_CREATE')")
     * @Template
     */
    public function createAction(Request $request)
    {
        $account = new Account();

        $company = $this->getCurrentCompany();

        if ($company) {
            $account->setCompany($company);
        }

        return $this->processForm($request, $account);
    }

    /**
     * @Route("/{id}/edit", name="account_edit")
     * @Security("is_granted('ACCOUNT_EDIT', account)")
     * @Template
     */
    public function editAction(Request $request, Account $account)
    {
        return $this->processForm($request, $account);
    }

    /**
     * @Route("/{id}/delete", name="account_delete")
     * @Security("is_granted('ACCOUNT_DELETE', account)")
     */
    public function deleteAction(Account $account)
    {
        $this->delete($account);

        $this->addFlash('success', 'flash.account.deleted', ['%account%' => $account]);

        return $this->redirectToRoute('accounts');
    }

    private function processForm(Request $request, Account $account = null)
    {
        /** @var DefaultAuthenticatedFormProcessor $processor */
        $processor = $this->get('account_form_processor');

        $processor->process($request, $account);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.account.created' : 'flash.account.updated', [
                '%account%' => $processor->getData()
            ]);

            if ($processor->isRedirectingTo(DefaultAuthenticatedFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('accounts');
            }

            return $this->redirectToRoute('account_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}

