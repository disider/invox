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

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use AppBundle\Entity\PaymentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentTypeForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('days', NumberType::class, [
            'label' => 'fields.days',
            'required' => false,
        ]);
        $builder->add('endOfMonth', CheckboxType::class, [
            'label' => 'fields.end_of_month',
            'required' => false,
        ]);
        $builder->add('translations', TranslationsType::class, [
            'label' => false,
            'fields' => [
                'name' => [
                    'field_type' => TextType::class,
                    'label' => 'fields.name',
                ],
            ]
        ]);

        $builder->add('save', SubmitType::class, ['label' => 'actions.save']);
        $builder->add(
            'saveAndClose',
            SubmitType::class,
            [
                'label' => 'actions.save_and_close',
                'button_class' => 'btn btn-default',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'paymentType';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaymentType::class,
            'cascade_validation' => true,
        ]);
    }

}
