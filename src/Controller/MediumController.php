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

use App\Entity\Medium;
use App\Form\Processor\MediumFormProcessor;
use App\Repository\MediumRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class MediumController extends BaseController
{

    /**
     * @Route("", name="media")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $filters[MediumRepository::FILTER_BY_COMPANY] = $this->getCurrentCompany();
        $query = $this->getMediumRepository()->findAllQuery($filters);

        $pagination = $this->paginate($query, $page, $pageSize, 'medium.fileName', 'asc');

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/browse", name="media_browse")
     * @Template
     */
    public function browseAction(Request $request)
    {
        $editorReference = $request->get('CKEditorFuncNum', 'none');

        $page = $this->getPage($request);
        $pageSize = $this->getPageSize($request);

        $filters = [
            MediumRepository::FILTER_BY_MIME_TYPE => ['png', 'jpg', 'jpeg', ''],
            MediumRepository::FILTER_BY_COMPANY => $this->getCurrentCompany(),
        ];

        $query = $this->getMediumRepository()->findAllQuery($filters);

        $pagination = $this->paginate($query, $page, $pageSize, 'medium.fileName', 'asc');

        return [
            'pagination' => $pagination,
            'editorRef' => $editorReference,
        ];
    }

    /**
     * @Route("/new", name="medium_create")
     * @Template
     */
    public function createAction(Request $request, MediumFormProcessor $processor)
    {
        $medium = new Medium();
        $medium->setContainer($this->getCurrentCompany());

        return $this->processForm($request, $processor, $medium);
    }

    /**
     * @Route("/{id}/edit", name="medium_edit")
     * @Template
     */
    public function editAction(Request $request, MediumFormProcessor $processor, Medium $medium)
    {
        return $this->processForm($request, $processor, $medium);
    }

    /**
     * @Route("/{id}/delete", name="medium_delete")
     */
    public function deleteAction(Medium $medium)
    {
        $this->delete($medium);

        @unlink($medium->getPath());

        $this->addFlash('success', 'flash.medium.deleted');

        return $this->redirectToRoute('media');
    }

    private function processForm(Request $request, MediumFormProcessor $processor, Medium $medium)
    {
        $processor->process($request, $medium);

        if ($processor->isValid()) {
            $this->addFlash(
                'success',
                $processor->isNew() ? 'flash.medium.created' : 'flash.medium.updated',
                ['%medium%' => $processor->getData()]
            );

            if ($processor->isRedirectingTo(MediumFormProcessor::REDIRECT_TO_LIST)) {
                return $this->redirectToRoute('media');
            }

            return $this->redirectToRoute(
                'medium_edit',
                [
                    'id' => $processor->getData()->getId(),
                ]
            );
        }

        $form = $processor->getForm();

        return [
            'form' => $form->createView(),
        ];
    }

}
