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
use AppBundle\Entity\Repository\CityRepository;
use AppBundle\Entity\Repository\CustomerRepository;
use AppBundle\Entity\Repository\DocumentRepository;
use AppBundle\Entity\Repository\EntityRepository;
use AppBundle\Entity\Repository\PettyCashNoteRepository;
use AppBundle\Entity\Repository\ProductRepository;
use AppBundle\Entity\Repository\ServiceRepository;
use AppBundle\Entity\Repository\TagRepository;
use AppBundle\Entity\Repository\WarehouseRecordRepository;
use AppBundle\Entity\Repository\WorkingNoteRepository;
use AppBundle\Entity\Repository\ZipCodeRepository;
use AppBundle\Entity\User;
use AppBundle\Mailer\Mailer;
use AppBundle\Model\Module;
use AppBundle\Problem\JsonProblem;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Translation\Translator;

/**
 * @method User getUser()
 */
class BaseController extends Controller
{
    /** @return Mailer $mailer */
    protected function getMailer()
    {
        return $this->get('default_mailer');
    }

    protected function getUserManager()
    {
        return $this->get('user_manager');
    }

    protected function isAuthenticated()
    {
        return $this->get('security.authorization_checker')->isGranted('ROLE_USER');
    }

    protected function authenticateUser(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
    }

    protected function translate($id, array $params = [], $domain = null)
    {
        /** @var Translator $translator */
        $translator = $this->get('translator');

        /* @Ignore */

        return $translator->trans($id, $params, $domain);
    }

    protected function applyFiltering(Request $request, Form $filterForm)
    {
        $filterForm->handleRequest($request);

        return $filterForm->isValid() ? $filterForm->getData()->getFilters() : [];
    }

    protected function save($object)
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $em->persist($object);
        $em->flush();
    }

    protected function delete($object)
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();
        $em->remove($object);

        $em->flush();
    }

    protected function getAccountRepository()
    {
        return $this->getRepository('AppBundle:Account');
    }

    /** @return CityRepository */
    protected function getCityRepository()
    {
        return $this->getRepository('AppBundle:City');
    }

    protected function getCompanyRepository()
    {
        return $this->getRepository('AppBundle:Company');
    }

    protected function getCountryRepository()
    {
        return $this->getRepository('AppBundle:Country');
    }

    /** @return CustomerRepository */
    protected function getCustomerRepository()
    {
        return $this->getRepository('AppBundle:Customer');
    }

    /** @return DocumentRepository */
    protected function getDocumentRepository()
    {
        return $this->getRepository('AppBundle:Document');
    }

    /** @return TagRepository */
    protected function getDocumentCostCenterRepository()
    {
        return $this->getRepository('AppBundle:DocumentCostCenter');
    }

    protected function getDocumentTemplateRepository()
    {
        return $this->getRepository('AppBundle:DocumentTemplate');
    }

    protected function getDocumentTemplatePerCompanyRepository()
    {
        return $this->getRepository('AppBundle:DocumentTemplatePerCompany');
    }

    protected function getLogRepository()
    {
        return $this->getRepository('AppBundle:Log');
    }

    protected function getMediumRepository()
    {
        return $this->getRepository('AppBundle:Medium');
    }

    protected function getPageRepository()
    {
        return $this->getRepository('AppBundle:Page');
    }

    protected function getPaymentTypeRepository()
    {
        return $this->getRepository('AppBundle:PaymentType');
    }

    protected function getParagraphTemplateRepository()
    {
        return $this->getRepository('AppBundle:ParagraphTemplate');
    }

    /** @return PettyCashNoteRepository */
    protected function getPettyCashNoteRepository()
    {
        return $this->getRepository('AppBundle:PettyCashNote');
    }

    /** @return ProductRepository */
    protected function getProductRepository()
    {
        return $this->getRepository('AppBundle:Product');
    }

    /** @return TagRepository */
    protected function getProductTagRepository()
    {
        return $this->getRepository('AppBundle:ProductTag');
    }

    protected function getProvinceRepository()
    {
        return $this->getRepository('AppBundle:Province');
    }

    /** @return ServiceRepository */
    protected function getServiceRepository()
    {
        return $this->getRepository('AppBundle:Service');
    }

    protected function getRecurrenceRepository()
    {
        return $this->getRepository('AppBundle:Recurrence');
    }

    /** @return TagRepository */
    protected function getServiceTagRepository()
    {
        return $this->getRepository('AppBundle:ServiceTag');
    }

    protected function getTaxRateRepository()
    {
        return $this->getRepository('AppBundle:TaxRate');
    }

    protected function getUserRepository()
    {
        return $this->getRepository('AppBundle:User');
    }

    /** @return WarehouseRecordRepository */
    protected function getWarehouseRecordRepository()
    {
        return $this->getRepository('AppBundle:WarehouseRecord');
    }

    /** @return WorkingNoteRepository */
    protected function getWorkingNoteRepository()
    {
        return $this->getRepository('AppBundle:WorkingNote');
    }

    /** @return ZipCodeRepository */
    protected function getZipCodeRepository()
    {
        return $this->getRepository('AppBundle:ZipCode');
    }

    /** @return EntityManager */
    protected function getEntityManager()
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    /** @return EntityRepository */
    protected function getRepository($name)
    {
        return $this->getEntityManager()->getRepository($name);
    }

    protected function serialize($object)
    {
        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($object, 'json'));
        $response->headers->add([
            'Content-Type' => 'application/json'
        ]);

        return $response;
    }

    /** @return Company */
    protected function getCurrentCompany()
    {
        return $this->get('company_manager')->getCurrent();
    }

    protected function hasCurrentCompany()
    {
        return $this->get('company_manager')->hasCurrent();
    }

    protected function setCurrentCompany(Company $company = null)
    {
        $this->get('company_manager')->setCurrent($company);
    }

    protected function canManageCurrentCompany()
    {
        if (!$this->hasCurrentCompany()) {
            return false;
        }

        return $this->getUser()->canManageCompany($this->getCurrentCompany());
    }

    protected function isCurrentAccountant()
    {
        if (!$this->hasCurrentCompany()) {
            return false;
        }

        return $this->getUser()->isAccountantFor($this->getCurrentCompany());
    }

    protected function isCurrentSalesAgent()
    {
        if (!$this->hasCurrentCompany()) {
            return false;
        }

        return $this->getUser()->isSalesAgentFor($this->getCurrentCompany());
    }

    protected function buildFilterForm(Request $request, QueryBuilder $queryBuilder, $filterForm, $options = [])
    {
        $filterForm = $this->createForm($filterForm, null, $options);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->get($filterForm->getName()));

            $lexik = $this->get('lexik_form_filter.query_builder_updater');
            $lexik->addFilterConditions($filterForm, $queryBuilder);
        }

        return $filterForm;
    }

    protected function paginate($query, $page, $pageSize, $sortField = '', $sortDirection = 'asc')
    {
        $paginator = $this->get('knp_paginator');

        if (in_array($this->getParameter('kernel.environment'), ['prod', 'dev'])) {
            $options = [
                'defaultSortFieldName' => $sortField,
                'defaultSortDirection' => $sortDirection
            ];
        }
        else {
            $options = [];
        }

        return $paginator->paginate(
            $query,
            $page,
            $pageSize,
            $options
        );
    }

    protected function getPageSize(Request $request)
    {
        return $request->get('pageSize', $this->container->getParameter('page_size'));
    }

    protected function getPage(Request $request)
    {
        return $request->get('page', 1);
    }

    protected function getCurrentLocale()
    {
        return $this->get('request_stack')->getMasterRequest()->getLocale();
    }

    protected function disableProfiler()
    {
        if ($this->container->has('profiler')) {
            $this->container->get('profiler')->disable();
        }
    }

    protected function isModuleEnabled($module)
    {
        return $this->getCurrentCompany()->hasModule(new Module($module));
    }

    protected function createJsonProblem($title, $statusCode)
    {
        $problem = new JsonProblem($title, $statusCode);
        $response = new Response($problem->format(), $problem->getStatusCode());
        $response->headers->set('ContentType', 'application/problem+json');
        return $response;
    }

    protected function isInDemoMode()
    {
        return $this->get('parameter_helper')->isInDemoMode();
    }
}
