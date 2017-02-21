<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Paragraph;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParagraphCollectionType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['prototype_id'] = $options['prototype_id'];

        $view->vars['level'] = isset($view->parent->vars['level']) ? $view->parent->vars['level'] + 1 : 0;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'entry_type' => ParagraphType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'error_bubbling' => false,
            'required' => false,
            'prototype' => true,
            'prototype_id' => 'paragraph-template',
            'prototype_data' => new Paragraph(),
            'delete_empty' => true,
            'attr' => [
                'class' => 'collection',
            ],
            'entry_options' => [
                'label' => false,
                'prototype' => false,
            ]
        ]);
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'paragraphs';
    }

}
