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

use AppBundle\Entity\Repository\ServiceRepository;
use AppBundle\Entity\Service;
use AppBundle\Form\Filter\ServiceFilterForm;
use AppBundle\Form\Processor\DefaultFormProcessor;
use AppBundle\Model\Module;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/services")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class ServiceController extends BaseController
{
    /**
     * @Route("", name="services")
     * @Security("is_granted('LIST_SERVICES')")
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
            $filters[ServiceRepository::FILTER_BY_MANAGER] = $user;
        }

        $query = $this->getServiceRepository()->findAllQuery($filters, $page, $pageSize);

        $filterForm = $this->buildFilterForm($request, $query, ServiceFilterForm::class);

        $pagination = $this->paginate($query, $page, $pageSize, 'service.name', 'asc');

        return [
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ];
    }

    /**
     * @Route("/new", name="service_create")
     * @Security("is_granted('SERVICE_CREATE')")
     * @Template
     */
    public function createAction(Request $request)
    {
        $service = new Service();

        $company = $this->getCurrentCompany();

        if ($company) {
            $service->setCompany($company);
        }

        return $this->processForm($request, $service);
    }

    /**
     * @Route("/{id}/edit", name="service_edit")
     * @Security("is_granted('SERVICE_EDIT', service)")
     * @Template
     */
    public function editAction(Request $request, Service $service)
    {
        return $this->processForm($request, $service);
    }

    /**
     * @Route("/{id}/delete", name="service_delete")
     * @Security("is_granted('SERVICE_DELETE', service)")
     */
    public function deleteAction(Service $service)
    {
        $this->delete($service);

        $this->addFlash('success', 'flash.service.deleted', ['%service%' => $service]);

        return $this->redirectToRoute('services');
    }

    /**
     * @Route("/search", name="service_search")
     */
    public function searchAction(Request $request)
    {
        if(!$this->isModuleEnabled(Module::SERVICES_MODULE)) {
            return $this->createJsonProblem('Module not enabled', 400);
        }

        $term = $request->get('term');

        $filters = [];

        $services = $this->getServiceRepository()->search($term, $this->getCurrentCompany(), $filters);

        return $this->serialize([
            'services' => $services,
        ]);
    }

    /**
     * @Route("/tags/search", name="service_tags_search")
     */
    public function searchTagsAction(Request $request)
    {
        if (!$this->isModuleEnabled(Module::SERVICES_MODULE)) {
            return $this->createJsonProblem('Module not enabled', 400);
        }

        $term = $request->get('term');

        $tags = $this->getServiceTagRepository()->search($term, $this->getCurrentCompany());

        return $this->serialize([
            'tags' => $tags,
        ]);
    }

    private function processForm(Request $request, Service $service = null)
    {
        /** @var DefaultFormProcessor $processor */
        $processor = $this->get('service_form_processor');

        $processor->process($request, $service);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.service.created' : 'flash.service.updated',
                ['%service%' => $processor->getData()]);

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('services');
            }

            return $this->redirectToRoute('service_edit', [
                'id' => $processor->getData()->getId(),
            ]);
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
