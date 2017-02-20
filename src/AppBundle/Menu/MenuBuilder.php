<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Menu;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Security\Voter\CompanyVoter;
use AppBundle\Security\Voter\RoleVoter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Translation\Translator;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function userMenu(FactoryInterface $factory, array $options)
    {
        /** @var ItemInterface $menu */
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'user-menu nav navbar-nav');

        if ($this->isAuthenticated()) {
            if ($this->isGranted(User::ROLE_USER)) {
                $url = $this->generateUrl('profile_edit');

                $user = $this->getUser();

                if ($this->canShowSelectedCompany()) {
                    $this->addCurrentCompanyMenu($menu);
                }
                else {
                    if ($user->isOwner() && $user->hasOwnedCompanies()) {
                        $this->addCompanyMenu($menu, $this->getCurrentCompany(), 'company_edit');
                    }
                }

                $welcome = $menu->addChild($this->translate('menu.welcome', [
                    '%url%' => $url,
                    '%user%' => $user,
                    '%exit_impersonate%' => $this->isGranted(User::ROLE_PREVIOUS_ADMIN)
                        ? sprintf('<a class="navbar-link" href="%s">(%s)</a>',
                            $this->generateUrl('dashboard', ['_switch_user' => '_exit']),
                            $this->translate('actions.exit_impersonate'))
                        : null,
                ]));
                $welcome->setAttribute('class', 'navbar-text');
                $welcome->setExtra('safe_label', true);

                $menu->addChild($this->translate('menu.logout'), ['route' => 'logout']);
            }
            else {
                if ($this->container->getParameter('show_registration')) {
                    $menu->addChild($this->translate('menu.register'), ['route' => 'register']);
                }

                $menu->addChild($this->translate('menu.login'), ['route' => 'login']);
            }

            $this->addLanguageMenus($menu);
        }

        return $menu;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', ['allow_safe_labels' => true]);
        $menu->setChildrenAttribute('class', 'main-menu');

        $this->addMenu($menu, 'tachometer', 'dashboard', 'dashboard', 'menu.dashboard');

        if ($this->isAuthenticated()) {
            $this->addMenu($menu, 'user', 'users', 'users', 'menu.users', User::ROLE_SUPER_ADMIN);
            $this->addMenu($menu, 'industry', 'companies\/new', 'company_create', 'menu.add_first_company', RoleVoter::FIRST_COMPANY_CREATE);
            $this->addMenu($menu, 'industry', 'companies', 'companies', 'menu.companies', RoleVoter::LIST_COMPANIES);
            $this->addMenu($menu, 'envelope-o', 'invites', 'invites', 'menu.invites', RoleVoter::LIST_INVITES);
        }

        if ($this->hasCurrentCompany()) {
            $this->addMenu($menu, 'users', 'customers', 'customers', 'menu.customers', RoleVoter::LIST_CUSTOMERS);
            $this->addMenu($menu, 'university', 'accounts', 'accounts', 'menu.accounts', RoleVoter::LIST_ACCOUNTS);

            $child = $this->addMenu($menu, 'book', '', '', 'menu.documents', RoleVoter::LIST_DOCUMENTS, [
                'uri' => '#documents',
                'showCaret' => true,
            ]);

            if ($child) {
                $child->setLinkAttributes([
                    'class' => 'collapsible collapsed',
                    'data-toggle' => 'collapse',
                ]);
                $child->setChildrenAttributes([
                    'id' => 'documents',
                    'class' => 'nav collapse submenu',
                ]);
                $this->addMenu($child, '', 'quotes', 'quotes', 'menu.quotes', RoleVoter::LIST_QUOTES);
                $this->addMenu($child, '', 'orders', 'orders', 'menu.orders', RoleVoter::LIST_ORDERS);
                $this->addMenu($child, '', 'invoices', 'invoices', 'menu.invoices', RoleVoter::LIST_INVOICES);
                $this->addMenu($child, '', 'receipts', 'receipts', 'menu.receipts', RoleVoter::LIST_RECEIPTS);
                $this->addMenu($child, '', 'credit-notes', 'credit_notes', 'menu.credit_notes', RoleVoter::LIST_CREDIT_NOTES);
                $this->addMenu($child, '', 'working-notes', 'working_notes', 'menu.working_notes', RoleVoter::LIST_WORKING_NOTES);
                $this->addMenu($child, '', 'delivery-notes', 'delivery_notes', 'menu.delivery_notes', RoleVoter::LIST_DELIVERY_NOTES);
            }

            $this->addMenu($menu, 'money', 'petty-cash-notes', 'petty_cash_notes', 'menu.petty_cash_notes', RoleVoter::LIST_PETTY_CASH_NOTES);
            $this->addMenu($menu, 'clock-o', 'recurrences', 'recurrences', 'menu.recurrences', RoleVoter::LIST_RECURRENCES);
            $this->addMenu($menu, 'shopping-cart', 'products', 'products', 'menu.products', RoleVoter::LIST_PRODUCTS);
            $this->addMenu($menu, 'server', 'services', 'services', 'menu.services', RoleVoter::LIST_SERVICES);
            $this->addMenu($menu, 'file', 'media', 'media', 'menu.media', RoleVoter::LIST_MEDIA);

            $child = $this->addMenu($menu, 'cog', '', '', 'menu.settings', RoleVoter::SHOW_SETTINGS, [
                'uri' => '#settings',
                'showCaret' => true,
            ]);

            if ($child) {
                $child->setLinkAttributes([
                    'class' => 'collapsible collapsed',
                    'data-toggle' => 'collapse',
                ]);
                $child->setChildrenAttributes([
                    'id' => 'settings',
                    'class' => 'nav collapse submenu',
                ]);

                $company = $this->getCurrentCompany();
                $link = $this->generateCurrentCompanyLink($company);
                $item = $child->addChild($company, ['uri' => $link]);

                $this->addMenu($child, '', 'modules', 'modules', 'menu.modules', RoleVoter::LIST_MODULES);
                $this->addMenu($child, '', 'paragraph-templates', 'paragraph_templates', 'menu.paragraph_templates', RoleVoter::LIST_PARAGRAPH_TEMPLATES);

                $this->addMenu($child, '', 'companies\/' . $company->getId() . '\/document-templates', 'document_templates_per_company', 'menu.document_templates', CompanyVoter::LIST_DOCUMENT_TEMPLATES_PER_COMPANY, [
                    'roleParameters' => $company,
                    'routeParameters' => [
                        'companyId' => $company->getId()
                    ]
                ]);
            }
        }

        return $menu;
    }

    public function superadminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', ['allow_safe_labels' => true]);
        $menu->setChildrenAttribute('class', 'superadmin-menu');

        if ($this->isGranted(User::ROLE_SUPER_ADMIN)) {

            $item = $menu->addChild($this->translate('menu.superadmin') . '&nbsp;<i class="collapse-toggle fa fa-caret-down"></i>', ['uri' => '#submenu']);
            $item->setExtra('safe_label', true);
            $item->setLinkAttribute('class', 'collapsible collapsed heading');
            $item->setLinkAttribute('data-toggle', 'collapse');
            $item->setChildrenAttribute('id', 'submenu');
            $item->setChildrenAttribute('class', 'nav collapse submenu');
            $item->setChildrenAttribute('data-parent', 'superadmin-menu');

            $this->addMenu($item, 'table', 'log', 'logs', 'menu.logs', User::ROLE_SUPER_ADMIN);
            $this->addMenu($item, 'globe', 'country', 'countries', 'menu.countries', User::ROLE_SUPER_ADMIN);
            $this->addMenu($item, 'globe', 'province', 'provinces', 'menu.provinces', User::ROLE_SUPER_ADMIN);
            $this->addMenu($item, 'globe', 'city', 'cities', 'menu.cities', User::ROLE_SUPER_ADMIN);
            $this->addMenu($item, 'globe', 'zip-code', 'zip_codes', 'menu.zip_codes', User::ROLE_SUPER_ADMIN);
            $this->addMenu($item, 'money', 'payment-types', 'payment_types', 'menu.payment_types', User::ROLE_SUPER_ADMIN);
            $this->addMenu($item, 'money', 'tax-rate', 'tax_rates', 'menu.tax_rates', User::ROLE_SUPER_ADMIN);
            $this->addMenu($item, 'file-o', 'page', 'pages', 'menu.pages', User::ROLE_SUPER_ADMIN);
            $this->addMenu($item, 'file-o', 'document-templates', 'document_templates', 'menu.document_templates', User::ROLE_SUPER_ADMIN);
        }

        return $menu;
    }

    private function isAuthenticated()
    {
        $tokenStorage = $this->getTokenStorage();

        return $tokenStorage->getToken() != null;
    }

    private function isGranted($role, $params = [])
    {
        /** @var AuthorizationChecker $authChecker */
        $authChecker = $this->container->get('security.authorization_checker');

        return $authChecker->isGranted($role, $params);
    }

    private function translate($id, array $params = [])
    {
        /** @var Translator $translator */
        $translator = $this->container->get('translator');

        /* @Ignore */
        return $translator->trans($id, $params);
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->getTokenStorage()->getToken()->getUser();
    }

    private function generateUrl($url, $params = [])
    {
        /** @var Router $router */
        $router = $this->container->get('router');

        return $router->generate($url, $params);
    }

    private function addMenu(ItemInterface $menu, $icon, $basePath, $route, $title, $role = null, $extraParams = [])
    {
        $request = $this->getRequest();

        $currentRoute = $request->get('_route');

        if ($icon) {
            $extraParams['titleParameters']['icon'] = $icon;
        }
        $roleParams = isset($extraParams['roleParameters']) ? $extraParams['roleParameters'] : [];

        $pathInfo = $request->getPathInfo();
        $matches = explode('/', $pathInfo);
        $currentPath = $matches[1];

        if (in_array($currentPath, $this->container->getParameter('available_locales'))) {
            $currentPath = $matches[2];
        }

        $currentPath = str_replace('-', '_', $currentPath);
        $isCurrent = ($route == $currentRoute) || ($basePath == $currentPath);

        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->isGranted($r, $roleParams)) {
                    return $this->addChild($menu, $route, $title, $isCurrent, $extraParams);
                }
            }
        }
        elseif ($role == null || $this->isGranted($role, $roleParams)) {
            return $this->addChild($menu, $route, $title, $isCurrent, $extraParams);
        }

        return null;
    }

    private function addLanguageMenus(ItemInterface $menu)
    {
        $request = $this->getRequest();

        $route = $request->get('_route') ?: 'dashboard';
        $routeParams = $request->get('_route_params') ?: [];
        $currentLocale = $request->get('_locale') ?: $this->container->getParameter('locale');

        $title = sprintf('<span class="lang-sm lang-is" lang="%s"></span>', $currentLocale);

        $item = $this->addDropdownMenu($menu, $title);

        $availableLocales = $this->container->getParameter('available_locales');

        foreach ($availableLocales as $locale) {
            $this->addLanguageMenu($item, 'locale.' . $locale, $route, $routeParams, $locale, $currentLocale);
        }
    }

    private function addLanguageMenu(ItemInterface $menu, $title, $route, $routeParams, $locale, $currentLocale)
    {
        $item = $menu->addChild(sprintf('<span class="lang-sm lang-is" lang="%s"></span> %s', $locale, $this->translate($title)), [
            'route' => $route,
            'routeParameters' => array_merge($routeParams, ['_locale' => $locale]),
        ]);
        $item->setExtra('safe_label', true);
        if ($locale == $currentLocale) {
            $item->setAttribute('class', 'active');
        }
    }

    private function addChild(ItemInterface $menu, $route, $title, $isCurrent, $extraParams = [])
    {
        $titleParams = isset($extraParams['titleParameters']) ? $extraParams['titleParameters'] : [];
        unset($extraParams['titleParameters']);
        $showCaret = isset($extraParams['showCaret']) ? $extraParams['showCaret'] : false;
        unset($extraParams['showCaret']);

        if (!empty($route)) {
            $extraParams['route'] = $route;
        }

        $title = $this->formatTitle($title, $titleParams);

        if ($showCaret) {
            $title .= '<i class="collapse-toggle fa fa-caret-down"></i></a>';
        }

        $child = $menu->addChild($title, $extraParams);
        $child->setExtra('safe_label', true);
        $child->setCurrent($isCurrent);

        return $child;
    }

    private function formatTitle($title, $extras)
    {
        $title = $this->translate($title);

        if (array_key_exists('badge', $extras)) {
            $title = sprintf('%s&nbsp;<span class="badge">%s</span>', $title, $extras['badge']);
        }

        if (array_key_exists('icon', $extras)) {
            $title = $this->formatIcon($title, $extras['icon']);
        }

        return $title;
    }

    /**
     * @return TokenStorageInterface
     */
    private function getTokenStorage()
    {
        return $this->container->get('security.token_storage');
    }

    private function addSeparator(ItemInterface $menu)
    {
        $menu->addChild('')->setAttribute('class', 'nav-divider');
    }

    private function getCurrentCompany()
    {
        return $this->container->get('company_manager')->getCurrent();
    }

    private function hasCurrentCompany()
    {
        return $this->container->get('company_manager')->hasCurrent();
    }

    /**
     * @return ItemInterface
     */
    private function addDropdownMenu(ItemInterface $menu, $title, array $parameters = [])
    {
        $item = $menu->addChild($title . '&nbsp;<span class="caret"></span>', ['uri' => '#']);
        $item->setExtra('safe_label', true);
        $item->setAttribute('class', 'dropdown');
        $item->setLinkAttributes([
            'class' => 'dropdown-toggle',
            'data-toggle' => 'dropdown',
            'role' => 'button',
            'aria-haspopup' => true,
            'aria-expanded' => false
        ]);
        $item->setChildrenAttribute('class', 'dropdown-menu');

        return $item;
    }

    /**
     * @return ItemInterface
     */
    private function addCompanyMenu(ItemInterface $menu, Company $company, $route, array $attributes = [])
    {
        $child = $menu->addChild($company, ['route' => $route, 'routeParameters' => ['id' => $company->getId()]]);
        $child->setAttributes($attributes);

        return $child;
    }

    private function canShowSelectedCompany()
    {
        $user = $this->getUser();

        return ($user->isSuperadmin() || $user->isAccountant() || $user->isSalesAgent() || $user->isManagingMultipleCompanies());
    }

    private function addCurrentCompanyMenu(ItemInterface $menu)
    {
        if (!$this->hasCurrentCompany()) {
            return;
        }

        $company = $this->getCurrentCompany();
        $link = $this->generateCurrentCompanyLink($company);

        $title = $this->formatIcon($company->getName(), 'industry');
        $title = sprintf('<p class="navbar-text"><a class="navbar-link" href="%s">%s</a>&nbsp;&nbsp;<a class="navbar-link" href="%s">%s</i></a></p>',
            $link,
            $title,
            $this->generateUrl('company_close_current'),
            '<i class="fa fa-times"></a>');
        $child = $menu->addChild($title);
        $child->setAttribute('class', 'current');
        $child->setExtra('safe_label', true);
    }

    private function formatIcon($title, $icon)
    {
        return sprintf('<i class="fa fa-%s"></i>&nbsp;&nbsp;%s', $icon, $title);
    }

    /**
     * @param $company
     * @return string
     */
    private function generateCurrentCompanyLink($company)
    {
        $user = $this->getUser();
        $link = $this->generateUrl(($user->isAccountantFor($company) || $user->isSalesAgentFor($company)) ? 'company_view' : 'company_edit'
            , ['id' => $company->getId()]);
        return $link;
    }

    /**
     * @return Request
     */
    private function getRequest()
    {
        /** @var RequestStack $requestStack */
        $requestStack = $this->container->get('request_stack');

        /** @var Request $request */
        $request = $requestStack->getMasterRequest();
        return $request;
    }
}
