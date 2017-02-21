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

use AppBundle\Entity\City;
use AppBundle\Form\Processor\DefaultFormProcessor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/cities")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class CityController extends BaseController
{
    /**
     * @Route("", name="cities")
     * @Security("is_granted('LIST_CITIES')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $this->getCityRepository()->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize, 'city.name', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="city_create")
     * @Security("is_granted('CITY_CREATE')")
     * @Template
     */
    public function createAction(Request $request)
    {
        $city = new City();

        return $this->processForm($request, $city);
    }

    /**
     * @Route("/{id}/edit", name="city_edit")
     * @Security("is_granted('CITY_EDIT', city)")
     * @Template
     */
    public function editAction(Request $request, City $city)
    {
        return $this->processForm($request, $city);
    }

    /**
     * @Route("/{id}/delete", name="city_delete")
     * @Security("is_granted('CITY_DELETE', city)")
     */
    public function deleteAction(City $city)
    {
        $this->delete($city);

        $this->addFlash('success', 'flash.city.deleted', ['%city%' => $city]);

        return $this->redirectToRoute('cities');
    }

    /**
     * @Route("/search", name="city_search")
     */
    public function searchAction(Request $request)
    {
        $term = $request->get('term');

        $cities = $this->getCityRepository()->search($term);

        return $this->serialize([
            'cities' => $cities,
        ]);
    }

    private function processForm(Request $request, City $city = null)
    {
        /** @var DefaultFormProcessor $processor */
        $processor = $this->get('city_form_processor');

        $processor->process($request, $city);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.city.created' : 'flash.city.updated',
                ['%city%' => $processor->getData()]);

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('cities');
            }

            return $this->redirectToRoute('city_edit', [
                    'id' => $processor->getData()->getId(),]
            );
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
