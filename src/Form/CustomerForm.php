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
use App\Entity\Customer;
use App\Entity\CustomerAttachment;
use App\Form\Type\AttachmentType;
use App\Form\Type\CollectionUploaderType;
use App\Form\Type\CountryEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerForm extends AbstractType
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

        // Contact details
        $builder->add('name', TextType::class, [
            'label' => 'fields.name',
        ]);
        $builder->add('vatNumber', TextType::class, [
            'label' => 'fields.vat_number',
        ]);
        $builder->add('fiscalCode', TextType::class, [
            'label' => 'fields.fiscal_code',
            'required' => false,
        ]);
        $builder->add('email', TextType::class, [
            'label' => 'fields.email',
            'required' => false,
        ]);
        $builder->add('referent', TextType::class, [
            'label' => 'fields.referent',
            'required' => false,
        ]);
        $builder->add('notes', TextareaType::class, [
            'label' => 'fields.notes',
            'required' => false,
        ]);

        // Address details
        $builder->add('phoneNumber', TextType::class, [
            'label' => 'fields.phone_number',
            'required' => false,
        ]);

        $builder->add('faxNumber', TextType::class, [
            'label' => 'fields.fax_number',
            'required' => false,
        ]);

        $builder->add('address', TextType::class, [
            'label' => 'fields.address',
            'required' => false,
        ]);

        $builder->add('zipCode', TextType::class, [
            'label' => 'fields.zip_code',
            'invalid_message' => 'error.invalid_zip_code',
            'required' => false,
        ]);

        $builder->add('city', TextType::class, [
            'label' => 'fields.city',
            'required' => false,
            'invalid_message' => 'error.invalid_city',
            'attr' => [
                'placeholder' => 'fields.autocomplete_city',
            ],
        ]);

        $builder->add('province', TextType::class, [
            'label' => 'fields.province',
            'required' => false,
            'invalid_message' => 'error.invalid_province',
        ]);

        $builder->add('country', CountryEntityType::class);
        $builder->add('addressNotes', TextareaType::class, [
            'label' => 'fields.address_notes',
            'required' => false,
        ]);

        $builder->add('attachments', CollectionUploaderType::class, [
            'label' => 'fields.attachments',
            'required' => false,
            'entry_type' => AttachmentType::class,
            'endpoint' => 'attachable',
            'entry_options' => [
                'data_class' => CustomerAttachment::class,
            ]
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'actions.save',
        ]);
        $builder->add('saveAndClose', SubmitType::class, [
            'label' => 'actions.save_and_close',
            'button_class' => 'btn btn-default',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'customer';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
