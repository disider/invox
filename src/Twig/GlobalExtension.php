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

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GlobalExtension extends AbstractExtension
{
    /**
     * @var bool
     */
    private $debug;

    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    public function getGlobals()
    {
        return [];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('novalidate', [$this, 'getNoValidate'], ['is_safe' => ['html']]),
        ];
    }

    public function getNoValidate()
    {
        return ''; //'novalidate';
    }
}
