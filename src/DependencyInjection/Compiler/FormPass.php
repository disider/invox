<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\DependencyInjection\Compiler;

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

        $resources[] = 'Form/country.html.twig';
        $resources[] = 'Form/collection_uploader.html.twig';
        $resources[] = 'Form/attachment.html.twig';
        $resources[] = 'Form/localized_number.html.twig';
        $resources[] = 'Form/tag.html.twig';
        $resources[] = 'Form/text_editor.html.twig';
        $resources[] = 'Form/uploader.html.twig';

        $container->setParameter('twig.form.resources', $resources);
    }
}
