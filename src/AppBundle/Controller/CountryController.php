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

use AppBundle\Entity\Country;
use AppBundle\Form\Processor\CountryFormProcessor;
use AppBundle\Form\Processor\DefaultFormProcessor;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/countries")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class CountryController extends BaseController
{
    /**
     * @Route("", name="countries")
     * @Security("is_granted('LIST_COUNTRIES')")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $query = $this->getCountryRepository()->findAllQuery([]);

        $pagination = $this->paginate($query, $page, $pageSize);

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/new", name="country_create")
     * @Security("is_granted('COUNTRY_CREATE')")
     * @Template
     */
    public function createAction(Request $request, CountryFormProcessor $processor)
    {
        $country = new Country();

        return $this->processForm($request, $processor, $country);
    }

    /**
     * @Route("/{id}/edit", name="country_edit")
     * @Security("is_granted('COUNTRY_EDIT', country)")
     * @Template
     */
    public function editAction(Request $request, CountryFormProcessor $processor, Country $country)
    {
        return $this->processForm($request, $processor, $country);
    }

    /**
     * @Route("/{id}/delete", name="country_delete")
     * @Security("is_granted('COUNTRY_DELETE', country)")
     */
    public function deleteAction(Country $country)
    {
        $this->delete($country);

        $this->addFlash('success', 'flash.country.deleted', ['%country%' => $country]);

        return $this->redirectToRoute('countries');
    }

    private function processForm(Request $request, CountryFormProcessor $processor, Country $country)
    {
        $processor->process($request, $country);

        if ($processor->isValid()) {
            $this->addFlash('success', $processor->isNew() ? 'flash.country.created' : 'flash.country.updated',
                ['%country%' => $processor->getData()]);

            if ($processor->isRedirectingTo(DefaultFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('countries');
            }

            return $this->redirect($this->generateUrl('country_edit', [
                    'id' => $processor->getData()->getId(),]
            ));
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }
}
