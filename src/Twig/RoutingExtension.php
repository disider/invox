<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Twig;

use Symfony\Bridge\Twig\Extension\RoutingExtension as BaseRoutingExtension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Translation\TranslatorInterface;

class RoutingExtension extends \Twig_Extension
{
    /**
     * @var BaseRoutingExtension $core
     */
    private $generator;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(UrlGeneratorInterface $generator, AuthorizationCheckerInterface $authorizationChecker, TranslatorInterface $translator)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->generator = $generator;
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('granted_path', [$this, 'getPath'], [
                'is_safe_callback' => [$this, 'isUrlGenerationSafe'],
            ]),
            new \Twig_SimpleFunction('format_create_link', [$this, 'formatCreateLink'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('format_edit_link', [$this, 'formatEditLink'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('format_delete_link', [$this, 'formatDeleteLink'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('format_view_link', [$this, 'formatViewLink'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('format_edit_icon', [$this, 'formatEditIcon'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('format_view_icon', [$this, 'formatViewIcon'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('format_delete_icon', [$this, 'formatDeleteIcon'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('format_action_icon', [$this, 'formatActionIcon'], [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFunction('format_action_link', [$this, 'formatActionLink'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getPath($name, $roleAttribute, $parameters = [], $subject = null, $relative = false)
    {
        if (!$this->authorizationChecker->isGranted($roleAttribute, $subject)) {
            throw new AccessDeniedException(sprintf('Cannot access %s path', $name));
        }

        return $this->generator->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function formatCreateLink($resource, array $parameters = [])
    {
        return $this->formatActionLink($resource, 'create', null, null, array_merge([
            'class' => 'create btn btn-primary',
        ], $parameters));
    }

    public function formatEditLink($resource, $object, $title, array $parameters = [])
    {
        return $this->formatActionLink($resource, 'edit', $title, $object, $parameters);
    }

    public function formatDeleteLink($resource, $object, $title, array $parameters = [])
    {
        return $this->formatActionLink($resource, 'delete', $title, $object, $parameters);
    }

    public function formatViewLink($resource, $object, $title, array $parameters = [])
    {
        return $this->formatActionLink($resource, 'view', $title, $object, $parameters);
    }

    public function formatEditIcon($resource, $object, array $parameters = [])
    {
        return $this->formatActionIcon($resource, 'edit', 'pencil', $object, $parameters);
    }

    public function formatViewIcon($resource, $object, array $parameters = [])
    {
        return $this->formatActionIcon($resource, 'view', 'eye', $object, $parameters);
    }

    public function formatDeleteIcon($resource, $object, array $parameters = [])
    {
        return $this->formatActionIcon($resource, 'delete', 'trash', $object, array_merge([
            'class' => 'btn btn-xs btn-default text-danger',
        ], $parameters));
    }

    public function formatActionIcon($resource, $action, $icon, $object, array $parameters = [])
    {
        return $this->formatActionLink($resource, $action, $this->formatIcon($icon), $object, array_merge([
            'class' => 'btn btn-xs btn-default',
        ], $parameters));
    }

    public function formatActionLink($resource, $action, $text, $object = null, array $parameters = [])
    {
        $routeName = $resource . '_' . $action;
        $role = strtoupper($routeName);
        $routeParameters = isset($parameters['routeParameters']) ? $parameters['routeParameters'] : [];
        $showDisabled = isset($parameters['enabled']);
        $enabled = isset($parameters['enabled']) ? $parameters['enabled'] : true;

        if ($object) {
            if ($this->authorizationChecker->isGranted($role, $object) && $enabled) {
                $title = $this->translate($resource . '.' . $action, ['%' . $resource . '%' => $object]);

                $url = $this->generator->generate($routeName, array_merge($routeParameters, ['id' => $object->getId()]));

                return $this->formatLink($action, $url, $title, $text, $parameters);
            }
        } else {
            if ($this->authorizationChecker->isGranted($role) && $enabled) {
                $title = $this->translate($resource . '.' . $action);

                $url = $this->generator->generate($routeName, $routeParameters);

                return $this->formatLink($action, $url, $title, $text, $parameters);
            }
        }

        return $showDisabled ? $this->formatLink($action, '#', '', $text, array_merge(['disabled' => true], $parameters)) : '';
    }

    public function isUrlGenerationSafe(\Twig_Node $argsNode)
    {
        // support named arguments
        $paramsNode = $argsNode->hasNode('parameters')
            ? $argsNode->getNode('parameters')
            : ($argsNode->hasNode(1)
                ? $argsNode->getNode(1)
                : null);

        if (null === $paramsNode || $paramsNode instanceof \Twig_Node_Expression_Array && count($paramsNode) <= 2 &&
            (!$paramsNode->hasNode(1) || $paramsNode->getNode(1) instanceof \Twig_Node_Expression_Constant)
        ) {
            return ['html'];
        }

        return [];
    }

    private function translate($id, array $params = [])
    {
        return str_replace('"', '&quot;', $this->translator->trans($id, $params));
    }

    private function formatLink($action, $url, $title, $text, array $parameters = [])
    {
        return sprintf('<a class="%s %s" href="%s" title="%s" %s>%s</a>',
            $action,
            isset($parameters['class']) ? $parameters['class'] : '',
            $url,
            $title,
            isset($parameters['disabled']) ? 'disabled="disabled"' : '',
            $text ? $text : $title
        );
    }

    private function formatIcon($icon)
    {
        return sprintf('<i class="fa fa-%s"></i>', $icon);
    }
}
