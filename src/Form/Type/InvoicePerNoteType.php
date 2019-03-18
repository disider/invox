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

use App\Entity\Document;
use App\Entity\InvoicePerNote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoicePerNoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'invoiceTitle',
            TextType::class,
            [
                'label' => 'fields.invoice',
                'required' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'class' => 'invoice-title',
                    'placeholder' => 'fields.autocomplete_invoice',
                ],
            ]
        );

        $builder->add(
            'invoice',
            EntityTextType::class,
            [
                'label' => false,
                'required' => false,
                'class' => Document::class,
                'invalid_message' => 'error.invalid_invoice',
                'attr' => [
                    'class' => 'invoice',
                ],
            ]
        );

        $builder->add(
            'amount',
            LocalizedNumberType::class,
            [
                'label' => 'fields.amount',
                'attr' => [
                    'class' => 'amount',
                ],
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'invoicePerNote';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => InvoicePerNote::class,
            ]
        );

        $resolver->setRequired(
            [
                'company',
            ]
        );
    }
}
