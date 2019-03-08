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

use App\Entity\Account;
use App\Entity\City;
use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Customer;
use App\Entity\Document;
use App\Entity\DocumentCostCenter;
use App\Entity\DocumentTemplate;
use App\Entity\DocumentTemplatePerCompany;
use App\Entity\Log;
use App\Entity\Manager\CompanyManager;
use App\Entity\Medium;
use App\Entity\Page;
use App\Entity\ParagraphTemplate;
use App\Entity\PaymentType;
use App\Entity\PettyCashNote;
use App\Entity\Product;
use App\Entity\ProductTag;
use App\Entity\Province;
use App\Entity\Recurrence;
use App\Entity\Service;
use App\Entity\ServiceTag;
use App\Entity\TaxRate;
use App\Entity\User;
use App\Entity\WarehouseRecord;
use App\Entity\WorkingNote;
use App\Entity\ZipCode;
use App\Helper\ParameterHelperInterface;
use App\Model\Module;
use App\Problem\JsonProblem;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method User getUser()
 */
class BaseController extends AbstractController
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
        return $this->getRepository(Account::class);
    }

    protected function getCityRepository()
    {
        return $this->getRepository(City::class);
    }

    protected function getCompanyRepository()
    {
        return $this->getRepository(Company::class);
    }

    protected function getCountryRepository()
    {
        return $this->getRepository(Country::class);
    }

    protected function getCustomerRepository()
    {
        return $this->getRepository(Customer::class);
    }

    protected function getDocumentRepository()
    {
        return $this->getRepository(Document::class);
    }

    protected function getDocumentCostCenterRepository()
    {
        return $this->getRepository(DocumentCostCenter::class);
    }

    protected function getDocumentTemplateRepository()
    {
        return $this->getRepository(DocumentTemplate::class);
    }

    protected function getDocumentTemplatePerCompanyRepository()
    {
        return $this->getRepository(DocumentTemplatePerCompany::class);
    }

    protected function getLogRepository()
    {
        return $this->getRepository(Log::class);
    }

    protected function getMediumRepository()
    {
        return $this->getRepository(Medium::class);
    }

    protected function getPageRepository()
    {
        return $this->getRepository(Page::class);
    }

    protected function getPaymentTypeRepository()
    {
        return $this->getRepository(PaymentType::class);
    }

    protected function getParagraphTemplateRepository()
    {
        return $this->getRepository(ParagraphTemplate::class);
    }

    protected function getPettyCashNoteRepository()
    {
        return $this->getRepository(PettyCashNote::class);
    }

    protected function getProductRepository()
    {
        return $this->getRepository(Product::class);
    }

    protected function getProductTagRepository()
    {
        return $this->getRepository(ProductTag::class);
    }

    protected function getProvinceRepository()
    {
        return $this->getRepository(Province::class);
    }

    protected function getRecurrenceRepository()
    {
        return $this->getRepository(Recurrence::class);
    }

    protected function getServiceRepository()
    {
        return $this->getRepository(Service::class);
    }

    protected function getServiceTagRepository()
    {
        return $this->getRepository(ServiceTag::class);
    }

    protected function getTaxRateRepository()
    {
        return $this->getRepository(TaxRate::class);
    }

    protected function getUserRepository()
    {
        return $this->getRepository(User::class);
    }

    protected function getWarehouseRecordRepository()
    {
        return $this->getRepository(WarehouseRecord::class);
    }

    protected function getWorkingNoteRepository()
    {
        return $this->getRepository(WorkingNote::class);
    }

    protected function getZipCodeRepository()
    {
        return $this->getRepository(ZipCode::class);
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }

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
