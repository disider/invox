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

use AppBundle\Model\LocaleFormatter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrencyListener implements EventSubscriberInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var \Twig_Environment */
    private $twigEnvironment;

    /**
     * @var LocaleFormatter
     */
    private $localeFormatter;

    public function __construct(TokenStorageInterface $tokenStorage, \Twig_Environment $twigEnvironment, LocaleFormatter $localeFormatter)
    {
        $this->tokenStorage = $tokenStorage;
        $this->twigEnvironment = $twigEnvironment;
        $this->localeFormatter = $localeFormatter;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        /** @var \Twig_Extension_Core $core */
        $core = $this->twigEnvironment->getExtension('core');

        $defaults = $this->localeFormatter->getDefaults();
        $decimal = $defaults[0];
        $decimalPoint = $defaults[1];
        $thousandSep = $defaults[2];

        $core->setNumberFormat($decimal, $decimalPoint, $thousandSep);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 0]],
        ];
    }
}
