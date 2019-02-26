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

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttachment;
use AppBundle\Form\Type\AttachmentType;
use AppBundle\Form\Type\CollectionUploaderType;
use AppBundle\Form\Type\LocalizedNumberType;
use AppBundle\Form\Type\TagType;
use AppBundle\Model\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        if ($user->isSuperadmin()) {
            $builder->add('company', EntityType::class, [
                'class' => 'AppBundle:Company',
                'label' => 'fields.company',
            ]);
        }

        $builder->add('name', TextType::class, ['label' => 'fields.name',]);
        $builder->add('code', TextType::class, ['label' => 'fields.code',]);

        $builder->add('unitPrice', LocalizedNumberType::class, [
            'label' => 'fields.unit_price',
            'required' => false,
        ]);
        $builder->add('measureUnit', TextType::class, [
            'label' => 'fields.measure_unit',
            'required' => false,
            'attr' => [
                'placeholder' => 'fields.optional',
            ]
        ]);
        $builder->add('taxRate', EntityType::class, [
            'label' => 'fields.vat',
            'class' => 'AppBundle\Entity\TaxRate',
            'required' => false,
            'placeholder' => 'fields.use_customer_tax_rate',
        ]);
        $builder->add('tags', TagType::class, [
            'label' => 'fields.tags',
            'required' => false,
            'route' => 'product_tags_search',
        ]);
        $builder->add('description', TextareaType::class, [
            'label' => 'fields.description',
            'required' => false,
        ]);
        $builder->add('internalNotes', TextareaType::class, [
            'label' => 'fields.internal_notes',
            'required' => false,
        ]);

        $builder->add('attachments', CollectionUploaderType::class, [
            'label' => 'fields.attachments',
            'required' => false,
            'entry_type' => AttachmentType::class,
            'endpoint' => 'attachable',
            'entry_options' => [
                'data_class' => ProductAttachment::class,
            ]
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data) {
                if ($data->getCompany()->hasModule(new Module(Module::WAREHOUSE_MODULE))) {
                    $form->add('enabledInWarehouse', CheckboxType::class, [
                        'label' => 'fields.enabled_in_warehouse',
                        'required' => false,
                    ]);
                    $form->add('initialStock', LocalizedNumberType::class, ['label' => 'fields.initial_stock',]);
                    $form->add('currentStock', LocalizedNumberType::class, [
                        'label' => 'fields.current_stock',
                        'disabled' => true,
                    ]);
                }
            }
        });

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

    public function getBlockPrefix()
    {
        return 'product';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');

        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
