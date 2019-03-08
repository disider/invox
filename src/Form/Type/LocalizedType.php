<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Type;

use App\Model\LocaleFormatter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class LocalizedType extends AbstractType
{
    /**
     * @var LocaleFormatter
     * @deprecated move to custom option
     */
    protected $localeFormatter;

    public function __construct(LocaleFormatter $localeFormatter)
    {
        $this->localeFormatter = $localeFormatter;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $defaults = $this->localeFormatter->getDefaults();
        $view->vars['decimalPoint'] = $defaults[1];
        $view->vars['thousandSeparator'] = $defaults[2];
    }
}
