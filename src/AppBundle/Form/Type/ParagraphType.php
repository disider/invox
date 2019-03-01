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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParagraphType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['label' => 'fields.title']);
        $builder->add('description', TextEditorType::class, [
            'label' => 'fields.description',
            'required' => false
        ]);

        $builder->add('children', ParagraphCollectionType::class, [
            'prototype' => false
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['level'] = isset($view->parent->vars['level']) ? $view->parent->vars['level'] : 0;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Paragraph::class,
            'prototype' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'paragraph';
    }
}

