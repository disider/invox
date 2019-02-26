<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Twig;

class GlobalExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var bool
     */
    private $debug;

    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('novalidate', [$this, 'getNoValidate'], ['is_safe' => ['html']]),
        ];
    }

    public function getNoValidate()
    {
        return 'novalidate';
    }
}
