<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form;

use AppBundle\Entity\DocumentTemplate;
use AppBundle\Form\Type\ColorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentTemplateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'fields.name'
        ]);

        $builder->add('textColor', ColorType::class, [
            'label' => 'fields.text_color'
        ]);

        $builder->add('tableHeaderBackgroundColor', ColorType::class, [
            'label' => 'fields.background_color',
        ]);

        $builder->add('tableHeaderColor', ColorType::class, [
            'label' => 'fields.text_color'
        ]);

        $builder->add('headingColor', ColorType::class, [
            'label' => 'fields.heading_color'
        ]);

        $builder->add('fontFamily', TextType::class, [
            'label' => 'fields.font_family'
        ]);

        $builder->add('header', TextareaType::class, [
            'label' => 'fields.header'
        ]);

        $builder->add('content', TextareaType::class, [
            'label' => 'fields.content'
        ]);

        $builder->add('footer', TextareaType::class, [
            'label' => 'fields.footer'
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'actions.save'
        ]);

        $builder->add('saveAndClose', SubmitType::class, [
            'button_class' => 'btn btn-default',
            'label' => 'actions.save_and_close'
        ]);

    }

    public function getBlockPrefix()
    {
        return 'document_template';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DocumentTemplate::class,
        ]);
    }
}
