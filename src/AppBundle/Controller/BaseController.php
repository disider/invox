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
use AppBundle\Entity\Manager\CompanyManager;
use AppBundle\Entity\Repository\EntityRepository;
use AppBundle\Entity\User;
use AppBundle\Helper\ParameterHelperInterface;
use AppBundle\Model\Module;
use AppBundle\Problem\JsonProblem;
use AppBundle\Repository\CityRepository;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Repository\DocumentCostCenterRepository;
use AppBundle\Repository\DocumentRepository;
use AppBundle\Repository\PettyCashNoteRepository;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\ProductTagRepository;
use AppBundle\Repository\ServiceRepository;
use AppBundle\Repository\ServiceTagRepository;
use AppBundle\Repository\WarehouseRecordRepository;
use AppBundle\Repository\WorkingNoteRepository;
use AppBundle\Repository\ZipCodeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @method User getUser()
 */
class BaseController extends Controller
{
    private $companyManager;
    private $authorizationChecker;
    private $tokenStorage;
    private $translator;
    private $entityManager;
    private $serializer;
    private $parameterHelper;
    private $filterBuilderUpdater;
    private $paginator;
    private $requestStack;
    private $defaultPageSize;
    private $environment;
    private $profiler;

    public function __construct(
        CompanyManager $companyManager,
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ParameterHelperInterface $parameterHelper,
        FilterBuilderUpdaterInterface $filterBuilderUpdater,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        Profiler $profiler,
        $defaultPageSize,
        $environment
    ) {
        $this->companyManager = $companyManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->parameterHelper = $parameterHelper;
        $this->filterBuilderUpdater = $filterBuilderUpdater;
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
        $this->profiler = $profiler;
        $this->defaultPageSize = $defaultPageSize;
        $this->environment = $environment;
    }

    protected function isAuthenticated()
    {
        return $this->authorizationChecker->isGranted('ROLE_USER');
    }

    protected function authenticateUser(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }

    protected function translate($id, array $params = [], $domain = null)
    {
        return $this->translator->trans($id, $params, $domain);
    }

    protected function setLocale($locale)
    {
        return $this->translator->setLocale($locale);
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

    /** @return DocumentCostCenterRepository */
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

    /** @return ProductTagRepository */
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

    /** @return ServiceTagRepository */
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

    /** @return EntityManagerInterface */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /** @return EntityRepository */
    protected function getRepository($name)
    {
        return $this->getEntityManager()->getRepository($name);
    }

    protected function serialize($object)
    {
        $response = new Response($this->serializer->serialize($object, 'json'));
        $response->headers->add(
            [
                'Content-Type' => 'application/json',
            ]
        );

        return $response;
    }

    /** @return Company */
    protected function getCurrentCompany()
    {
        return $this->companyManager->getCurrent();
    }

    protected function hasCurrentCompany()
    {
        return $this->companyManager->hasCurrent();
    }

    protected function setCurrentCompany(Company $company = null)
    {
        $this->companyManager->setCurrent($company);
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

            $this->filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
        }

        return $filterForm;
    }

    protected function paginate($query, $page, $pageSize, $sortField = '', $sortDirection = 'asc')
    {
        if (in_array($this->environment, ['prod', 'dev'])) {
            $options = [
                'defaultSortFieldName' => $sortField,
                'defaultSortDirection' => $sortDirection,
            ];
        } else {
            $options = [];
        }

        return $this->paginator->paginate(
            $query,
            $page,
            $pageSize,
            $options
        );
    }

    protected function getPageSize(Request $request)
    {
        return $request->get('pageSize', $this->defaultPageSize);
    }

    protected function getPage(Request $request)
    {
        return $request->get('page', 1);
    }

    protected function getCurrentLocale()
    {
        return $this->requestStack->getMasterRequest()->getLocale();
    }

    protected function disableProfiler()
    {
        $this->profiler->disable();
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
        return $this->parameterHelper->isInDemoMode();
    }
}
