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

use AppBundle\Entity\Company;
use AppBundle\Form\Type\CountryEntityType;
use AppBundle\Model\DocumentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        if ($user->isSuperadmin()) {
            $builder->add('owner', EntityType::class, [
                'class' => 'AppBundle:User',
                'label' => 'fields.owner',
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
            'invalid_message' => 'error.invalid_city',
            'required' => false,
            'attr' => [
                'placeholder' => 'fields.autocomplete_city',
            ],
        ]);

        $builder->add('province', TextType::class, [
            'label' => 'fields.province',
            'invalid_message' => 'error.invalid_province',
            'required' => false,
        ]);

        $builder->add('country', CountryEntityType::class);

        $builder->add('addressNotes', TextareaType::class, [
            'label' => 'fields.address_notes',
            'required' => false,
        ]);

        $builder->add('logo', FileType::class, [
            'label' => 'fields.logo',
            'required' => false,
        ]);

        $builder->add('documentTypes', ChoiceType::class, [
            'choices' => $this->buildTypes(),
            'label' => 'fields.document_types',
            'expanded' => true,
            'multiple' => true,
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'actions.save',
        ]);

        $builder->add('saveAndClose', SubmitType::class, [
            'label' => 'actions.save_and_close',
            'button_class' => 'btn btn-default',
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Company $data */
            $data = $event->getData();
            $form = $event->getForm();

            if ($data && $data->hasLogoUrl()) {
                $form->add('deleteLogo', CheckboxType::class, [
                    'label' => 'actions.delete_logo',
                    'required' => false,
                ]);
            }
        });
    }

    public function getBlockPrefix()
    {
        return 'company';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }

    private function buildTypes()
    {
        $types = [];
        foreach (DocumentType::getAll() as $type) {
            $types['document.type.' . $type] = $type;
        };

        return $types;
    }
}
