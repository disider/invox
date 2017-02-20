<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');

        $resources[] = 'AppBundle:Form:country.html.twig';
        $resources[] = 'AppBundle:Form:uploader.html.twig';
        $resources[] = 'AppBundle:Form:collection_uploader.html.twig';
        $resources[] = 'AppBundle:Form:attachment.html.twig';
        $resources[] = 'AppBundle:Form:localized_number.html.twig';
        $resources[] = 'AppBundle:Form:tag.html.twig';
        $resources[] = 'AppBundle:Form:text_editor.html.twig';

        $container->setParameter('twig.form.resources', $resources);
    }
}
