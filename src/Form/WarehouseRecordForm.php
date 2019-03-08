<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form;

use App\Entity\WarehouseRecord;
use App\Form\Type\LocalizedNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WarehouseRecordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('loadQuantity', LocalizedNumberType::class, [
            'label' => 'fields.load_quantity',
            'invalid_message' => 'error.invalid_quantity',
        ]);
        $builder->add('unloadQuantity', LocalizedNumberType::class, [
            'label' => 'fields.unload_quantity',
            'invalid_message' => 'error.invalid_quantity',
        ]);
        $builder->add('purchasePrice', LocalizedNumberType::class, [
            'label' => 'fields.purchase_price',
            'invalid_message' => 'error.invalid_price',
        ]);
        $builder->add('sellPrice', LocalizedNumberType::class, [
            'label' => 'fields.sell_price',
            'invalid_message' => 'error.invalid_price',
        ]);
        $builder->add('date', DateType::class, [
            'label' => 'fields.date',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'datepicker'
            ]
        ]);
        $builder->add('description', TextType::class, ['label' => 'fields.sell_price']);

        $builder->add('add', SubmitType::class, [
            'label' => false,
            'attr' => [
                'icon' => 'plus',
                'title' => 'actions.add'
            ]
        ]);
    }

    public function getBlockPrefix()
    {
        return 'warehouseRecord';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WarehouseRecord::class,
        ]);
    }

}
