<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatorInterface;

class FormAuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session, TranslatorInterface $translator, RouterInterface $router)
    {
        $this->session = $session;
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * @param FormEvent $event
     */
    public function onFormSetData(FormEvent $event)
    {
        $error = $this->session->get(Security::AUTHENTICATION_ERROR);

        // Remove error so it isn't persisted
        $this->session->remove(Security::AUTHENTICATION_ERROR);

        if ($error) {
            $event->getForm()->addError(new FormError($this->formatError($error)));
        }

        $event->setData([
            '_username' => $this->session->get(Security::LAST_USERNAME),
        ]);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onFormSetData',
        ];
    }

    /**
     * @return string
     */
    private function formatError($error)
    {
        if ($error instanceof DisabledException) {
            $email = $error->getUser()->getUsername();

            $route = $this->generateRoute('register_resend_confirmation', [
                'email' => $email
            ]);

            return $this->translate('error.inactive_user', ['%url%' => $route], 'validators');
        }

        if ($error instanceof BadCredentialsException) {
            return $this->translate('error.invalid_credentials', [], 'validators');
        }

        return $error->getMessage();
    }

    private function translate($key, $params, $domain = 'messages')
    {
        return $this->translator->trans($key, $params, $domain);
    }

    private function generateRoute($route, $params)
    {
        return $this->router->generate($route, $params);
    }
}
