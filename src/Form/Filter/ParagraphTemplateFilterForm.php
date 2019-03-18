<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Filter;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ParagraphTemplateFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            null,
            [
                'label' => false,
                'attr' => ['placeholder' => 'fields.title'],
                'apply_filter' => $this->getFilterFunction(),
                'required' => false,
            ]
        );

        $builder->add('filter', SubmitType::class, ['label' => 'actions.filter']);;
    }

    public function getBlockPrefix()
    {
        return 'paragraphTemplatesFilter';
    }

}