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

use App\Entity\Company;
use App\Entity\Service;
use App\Entity\ServiceAttachment;
use App\Entity\TaxRate;
use App\Form\Type\AttachmentType;
use App\Form\Type\CollectionUploaderType;
use App\Form\Type\LocalizedNumberType;
use App\Form\Type\TagType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        if ($user->isSuperadmin()) {
            $builder->add('company', EntityType::class, [
                'class' => Company::class,
                'label' => 'fields.company',
            ]);
        }

        $builder->add('name', TextType::class, ['label' => 'fields.name',]);
        $builder->add('code', TextType::class, ['label' => 'fields.code',]);

        $builder->add('unitPrice', LocalizedNumberType::class, ['label' => 'fields.unit_price',]);
        $builder->add('measureUnit', TextType::class, [
            'label' => 'fields.measure_unit',
            'required' => false,
            'attr' => [
                'placeholder' => 'fields.optional',
            ]
        ]);
        $builder->add('taxRate', EntityType::class, [
            'label' => 'fields.vat',
            'class' => TaxRate::class,
            'required' => false,
            'placeholder' => 'fields.use_customer_tax_rate',
        ]);
        $builder->add('tags', TagType::class, [
            'label' => 'fields.tags',
            'required' => false,
            'route' => 'service_tags_search',
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
                'data_class' => ServiceAttachment::class,
            ]
        ]);

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
        return 'service';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');

        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
