<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form;

use AppBundle\Entity\ParagraphTemplate;
use AppBundle\Form\Type\ParagraphCollectionType;
use AppBundle\Form\Type\TextEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParagraphTemplateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, ['label' => 'fields.title']);
        $builder->add('description', TextEditorType::class, [
            'label' => 'fields.description',
            'required' => false
        ]);

        $builder->add('children', ParagraphCollectionType::class, [
            'prototype' => $options['prototype'],
            'prototype_data' => new ParagraphTemplate(),
            'entry_type' => ParagraphTemplateForm::class,
            'entry_options' => [
                'label' => false,
                'prototype' => false,
                'data_class' => ParagraphTemplate::class,
            ]
        ]);

        if($options['prototype']) {
            $builder->add('save', SubmitType::class, ['label' => 'actions.save',]);
            $builder->add(
                'saveAndClose',
                SubmitType::class,
                [
                    'label' => 'actions.save_and_close',
                    'button_class' => 'btn btn-default',
                ]
            );
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['level'] = isset($view->parent->vars['level']) ? $view->parent->vars['level'] : 0;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'data_class' => ParagraphTemplate::class,
            'prototype' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'paragraphTemplate';
    }
}

