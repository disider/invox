<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;

class IconExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('fa_icon', [$this, 'showIcon'], ['is_safe' => ['html']]),
        ];
    }

    public function showIcon($icon, $class = '')
    {
        return sprintf('<span class="fa fa-%s %s"></span>', $icon, $class ? 'fa-'.$class : '');
    }
}
