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

use AppBundle\Entity\Company;
use AppBundle\Entity\Invite;
use AppBundle\Form\Processor\CompanyFormProcessor;
use AppBundle\Form\Processor\InviteFormProcessor;
use AppBundle\Generator\TokenGenerator;
use AppBundle\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/companies")
 */
class CompanyController extends BaseController
{
    /**
     * @Route("", name="companies")
     * @Security("is_granted('LIST_COMPANIES')")
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
        } else {
            $filters[CompanyRepository::FILTER_BY_OWNER] = $user;
            $filters[CompanyRepository::FILTER_BY_ACCOUNTANT] = $user;
            $filters[CompanyRepository::FILTER_BY_SALES_AGENT] = $user;
        }

        $query = $this->getCompanyRepository()->findAllQuery($filters, $page, $pageSize);

        $pagination = $this->paginate($query, $page, $pageSize, 'company.name', 'asc');

        return [
            'pagination' => $pagination,
            'currentCompany' => $this->hasCurrentCompany() ? $this->getCurrentCompany() : null,
        ];
    }

    /**
     * @Route("/new", name="company_create")
     * @Security("is_granted('COMPANY_CREATE')")
     * @Template
     */
    public function createAction(Request $request, CompanyFormProcessor $processor)
    {
        $template = $this->getDocumentTemplateRepository()->findAll();

        $company = Company::createEmpty($this->getUser(), $template);

        return $this->processForm($request, $processor, $company);
    }

    /**
     * @Route("/{id}/edit", name="company_edit")
     * @Security("is_granted('COMPANY_EDIT', company)")
     * @Template
     */
    public function editAction(Request $request, CompanyFormProcessor $processor, Company $company)
    {
        return $this->processForm($request, $processor, $company);
    }

    /**
     * @Route("/{id}/delete", name="company_delete")
     * @Security("is_granted('COMPANY_DELETE', company)")
     */
    public function deleteAction(Company $company)
    {
        if ($this->isInDemoMode()) {
            $this->addFlash('danger', 'demo.action_not_allowed');
        } else {
            $this->getCompanyRepository()->delete($company);
            $this->addFlash('success', 'flash.company.deleted', ['%company%' => $company]);
        }

        return $this->redirectToRoute('companies');
    }

    /**
     * @Route("/{id}/view", name="company_view")
     * @Security("is_granted('COMPANY_VIEW', company)")
     * @Template
     */
    public function viewAction(Company $company)
    {
        return ['company' => $company];
    }

    /**
     * @Route("/{id}/select", name="company_select")
     * @Security("is_granted('COMPANY_SELECT', company)")
     */
    public function selectAction(Company $company)
    {
        $this->setCurrentCompany($company);

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/{id}/deselect", name="company_deselect")
     * @Security("is_granted('COMPANY_SELECT', company)")
     */
    public function deselectAction(Company $company)
    {
        $this->setCurrentCompany(null);

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/close-current", name="company_close_current")
     */
    public function closeCurrentAction()
    {
        $this->setCurrentCompany(null);

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/{id}/accountant", name="company_accountant")
     * @Template
     */
    public function accountantAction(Request $request, InviteFormProcessor $processor, Company $company)
    {
        if ($request->getMethod() == 'POST' && $this->isInDemoMode()) {
            $this->addFlash('danger', 'demo.action_not_allowed');
            return $this->redirectToRoute('company_accountant', [
                'id' => $company->getId(),
            ]);
        }

        return $this->processInviteForm($request, $processor, $company);
    }

    /**
     * @Route("/{id}/disconnect-accountant", name="company_disconnect_accountant")
     * @Security("is_granted('COMPANY_DISCONNECT_ACCOUNTANT', company)")
     */
    public function disconnectAccountantAction(Company $company)
    {
        if (!$company->hasAccountant()) {
            $this->addFlash('danger', 'flash.company.accountant_not_connected');
        } else if ($this->isInDemoMode()) {
            $this->addFlash('danger', 'demo.action_not_allowed');
        } else {
            $company->setAccountant(null);
            $this->save($company);
            $this->addFlash('success', 'flash.company.accountant_disconnected');
        }

        return $this->redirectToRoute('company_accountant', [
            'id' => $company->getId(),
        ]);
    }

    private function processForm(Request $request, CompanyFormProcessor $processor, Company $company)
    {
        $processor->process($request, $company);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.company.created' : 'flash.company.updated',
                ['%company%' => $processor->getData()]);

            if ($processor->isRedirectingTo(CompanyFormProcessor::REDIRECT_TO_LIST)) {
                $user = $this->getUser();

                if ($user->canManageMultipleCompanies()) {
                    return $this->redirectToRoute('companies');
                }

                return $this->redirectToRoute('dashboard');
            }

            return $this->redirectToRoute('company_edit', [
                'id' => $processor->getData()->getId()
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }

    private function processInviteForm(Request $request, InviteFormProcessor $processor, Company $company)
    {
        $invite = Invite::create($company, $this->getUser(), TokenGenerator::generateToken());
        $processor->process($request, $invite);

        if ($processor->isValid()) {
            $invite = $processor->getInvite();

            if ($processor->hasSentInvite()) {
                $this->addFlash('success', 'flash.company.accountant_invited', ['%accountant%' => $invite->getEmail()]);
            } else {
                $this->addFlash('info', 'flash.company.accountant_already_invited', ['%accountant%' => $invite->getEmail()]);
            }

            return $this->redirectToRoute('company_accountant', [
                'id' => $company->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
            'company' => $company
        ];
    }
}
