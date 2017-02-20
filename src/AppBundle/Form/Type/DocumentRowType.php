<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\DocumentRow;
use AppBundle\Entity\Product;
use AppBundle\Entity\Service;
use AppBundle\Entity\TaxRate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentRowType extends LocalizedType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'fields.title',
            'attr' => [
                'placeholder' => 'fields.placeholder.title',
            ],
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'fields.description',
            'attr' => [
                'placeholder' => 'fields.placeholder.description',
            ],
        ]);
        $builder->add('unitPrice', LocalizedNumberType::class, [
            'label' => 'fields.unit_price',
        ]);
        $builder->add('quantity', LocalizedNumberType::class, [
            'label' => 'fields.quantity',
        ]);

        $builder->add('taxRate', EntityType::class, [
            'class' => TaxRate::class,
            'label' => 'fields.tax_rate',
            'choice_attr' => function (TaxRate $choice, $key) {
                return [
                    'data-amount' => $choice->getAmount(),
                ];
            },
        ]);

        $builder->add('discount', LocalizedNumberType::class, [
            'label' => 'fields.discount_per_item',
        ]);
        $builder->add('discountPercent', DiscountPercentToggleType::class);
        $builder->add('grossCost', LocalizedNumberType::class, [
            'label' => 'fields.total',
            'attr' => [
                'disabled' => true,
            ],
        ]);

        $builder->add('linkedProduct', EntityTextType::class, [
            'label' => false,
            'required' => false,
            'class' => Product::class,
            'invalid_message' => 'error.invalid_product',
        ]);

        $builder->add('linkedService', EntityTextType::class, [
            'label' => false,
            'required' => false,
            'class' => Service::class,
            'invalid_message' => 'error.invalid_service',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'data_class' => DocumentRow::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'documentRow';
    }
}
