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

use AppBundle\Entity\Document;
use AppBundle\Form\ContactUsForm;
use AppBundle\Mailer\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/")
 */
class DefaultController extends BaseController
{

    /**
     * @Route("", name="homepage")
     * @Template
     */
    public function indexAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }

        if (!$this->getParameter('enable_homepage')) {
            return $this->redirectToRoute('login');
        }

        return [];
    }

    /**
     * @Route("dashboard", name="dashboard")
     * @Template
     */
    public function dashboardAction()
    {
        if ($this->hasCurrentCompany()) {
            $currentCompany = $this->getCurrentCompany();
            if (!$currentCompany->hasSalesAgent($this->getUser())) {
                $incomingInvoices = $this->getDocumentRepository()->findExpiredInvoices($this->getCurrentCompany(), Document::INCOMING);
                $outgoingInvoices = $this->getDocumentRepository()->findExpiredInvoices($this->getCurrentCompany(), Document::OUTGOING);

                return [
                    'currentCompany' => $currentCompany,
                    'incomingInvoices' => $incomingInvoices,
                    'outgoingInvoices' => $outgoingInvoices,
                ];
            }
        }

        return [];
    }

    /**
     * @Route("/contact-us", name="contact_us")
     * @Template
     */
    public function contactUsAction(Request $request)
    {
        $form = $this->createForm(ContactUsForm::class, null, [
            'debug' => $this->getParameter('kernel.debug'),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /** @var Mailer $mailer */
            $mailer = $this->get('default_mailer');
            $mailer->sendContactUsMail($data['email'], $data['subject'], $data['body']);

            $this->addFlash('success', 'contact_us.success');

            return $this->redirectToRoute('contact_us');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("privacy", name="privacy")
     * @Template
     */
    public function privacyAction()
    {
        return [];
    }

    /**
     * @Route("terms", name="terms")
     * @Template
     */
    public function termsAction()
    {
        return [];
    }


}
