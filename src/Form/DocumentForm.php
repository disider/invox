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
use App\Entity\Document;
use App\Entity\DocumentAttachment;
use App\Entity\DocumentTemplatePerCompany;
use App\Entity\PaymentType;
use App\Entity\Recurrence;
use App\Form\Type\AttachmentType;
use App\Form\Type\CollectionUploaderType;
use App\Form\Type\CountryEntityType;
use App\Form\Type\DiscountPercentToggleType;
use App\Form\Type\DocumentLinkType;
use App\Form\Type\DocumentRowCollectionType;
use App\Form\Type\EntityTextType;
use App\Form\Type\LocalizedNumberType;
use App\Form\Type\TagType;
use App\Form\Type\TextEditorType;
use App\Repository\DocumentTemplatePerCompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $company = $options['company'];

        $builder->add('type', ChoiceType::class, [
            'choices' => $this->buildTypes($company),
            'label' => 'fields.type',
            'expanded' => true,
            'attr' => [
                'class' => 'btn-group',
                'data-toggle' => 'buttons',
            ],
        ]);

        $builder->add('title', TextType::class, [
            'label' => 'fields.title',
            'required' => false,
            'attr' => [
                'placeholder' => 'fields.placeholder.title',
            ],
        ]);

        $builder->add('notes', TextareaType::class, [
            'label' => 'fields.notes',
            'required' => false
        ]);

        $builder->add('content', TextEditorType::class, [
            'label' => 'fields.content',
            'required' => false,
            'attr' => [
                'placeholder' => 'fields.placeholder.content',
            ],
        ]);

        $builder->add('internalRef', TextType::class, [
            'label' => 'fields.internal_ref',
            'required' => false,
            'attr' => [
                'placeholder' => 'fields.placeholder.internal_ref',
            ],
        ]);

        $builder->add('language', ChoiceType::class, [
            'label' => 'fields.language',
            'choices' => $this->buildLocales($options['available_locales'])
        ]);

        $builder->add('costCenters', TagType::class, [
            'label' => 'fields.cost_centers',
            'required' => false,
            'route' => 'document_cost_centers_search',
            'collection' => 'costCenters',
        ]);

        // Company details
        $builder->add('companyName', TextType::class, [
            'label' => 'fields.name',
            'required' => false,
        ]);
        $builder->add('companyVatNumber', TextType::class, [
            'label' => 'fields.vat_number',
            'required' => false,
        ]);
        $builder->add('companyFiscalCode', TextType::class, [
            'label' => 'fields.fiscal_code',
            'required' => false,
        ]);
        $builder->add('companyPhoneNumber', TextType::class, [
            'label' => 'fields.phone_number',
            'required' => false,
        ]);
        $builder->add('companyFaxNumber', TextType::class, [
            'label' => 'fields.fax_number',
            'required' => false,
        ]);
        $builder->add('companyAddress', TextType::class, [
            'label' => 'fields.address',
            'required' => false,
        ]);
        $builder->add('companyZipCode', TextType::class, [
            'label' => 'fields.zip_code',
            'required' => false,
            'invalid_message' => 'error.invalid_zip_code',
        ]);

        $builder->add('companyCity', TextType::class, [
            'label' => 'fields.city',
            'required' => false,
            'invalid_message' => 'error.invalid_city',
            'attr' => [
                'placeholder' => 'fields.autocomplete_city',
            ],
        ]);

        $builder->add('companyProvince', TextType::class, [
            'label' => 'fields.province',
            'required' => false,
            'invalid_message' => 'error.invalid_province',
        ]);

        $builder->add('companyCountry', CountryEntityType::class);

        $builder->add('companyLogo', FileType::class, [
            'label' => 'fields.logo',
            'required' => false,
        ]);

        $builder->add('companyAddressNotes', TextareaType::class, [
            'label' => 'fields.address_notes',
            'required' => false,
        ]);

        // Customer details
        $builder->add('linkedCustomer', EntityTextType::class, [
            'label' => false,
            'class' => Customer::class,
            'invalid_message' => 'error.invalid_customer',
        ]);

        $builder->add('customerName', DocumentLinkType::class, [
            'label' => 'fields.name',
            'attr' => [
                'placeholder' => 'fields.autocomplete_customer',
            ],
            'linked_to' => 'linkedCustomer',
            'type' => 'customer'
        ]);
        $builder->add('customerVatNumber', TextType::class, [
            'label' => 'fields.vat_number',
        ]);
        $builder->add('customerFiscalCode', TextType::class, [
            'label' => 'fields.fiscal_code',
            'required' => false,
        ]);
        $builder->add('customerPhoneNumber', TextType::class, [
            'label' => 'fields.phone_number',
            'required' => false,
        ]);
        $builder->add('customerFaxNumber', TextType::class, [
            'label' => 'fields.fax_number',
            'required' => false,
        ]);
        $builder->add('customerAddress', TextType::class, [
            'label' => 'fields.address',
            'required' => false,
        ]);
        $builder->add('customerZipCode', TextType::class, [
            'label' => 'fields.zip_code',
            'required' => false,
            'invalid_message' => 'error.invalid_zip_code',
        ]);

        $builder->add('customerCity', TextType::class, [
            'label' => 'fields.city',
            'required' => false,
            'invalid_message' => 'error.invalid_city',
            'attr' => [
                'placeholder' => 'fields.autocomplete_city',
            ],
        ]);

        $builder->add('customerProvince', TextType::class, [
            'label' => 'fields.province',
            'required' => false,
            'invalid_message' => 'error.invalid_province',
        ]);

        $builder->add('customerCountry', CountryEntityType::class);

        $builder->add('customerAddressNotes', TextareaType::class, [
            'label' => 'fields.address_notes',
            'required' => false,
        ]);

        $builder->add('ref', TextType::class, [
            'label' => 'fields.ref',
        ]);
        $builder->add('year', TextType::class, [
            'label' => 'fields.year',
        ]);
        $builder->add('discount', LocalizedNumberType::class, [
            'label' => 'fields.discount',
            'required' => false,
        ]);
        $builder->add('discountPercent', DiscountPercentToggleType::class);

        $builder->add('rounding', LocalizedNumberType::class, [
            'label' => 'fields.rounding',
            'required' => false,
        ]);
        $builder->add('issuedAt', DateType::class, [
            'label' => 'fields.issued_at',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
        ]);

        $builder->add('paymentType', EntityType::class, [
            'label' => 'fields.payment_type',
            'class' => PaymentType::class,
            'choice_attr' => function (PaymentType $paymentType, $key) {
                return [
                    'data-days' => $paymentType->getDays(),
                    'data-eom' => $paymentType->getEndOfMonth() ? 'true' : 'false',
                ];
            },
        ]);

        $builder->add('documentTemplate', EntityType::class, [
            'label' => 'fields.template',
            'class' => DocumentTemplatePerCompany::class,
            'query_builder' => function (DocumentTemplatePerCompanyRepository $repo) use ($company) {
                $filters = [
                    DocumentTemplatePerCompanyRepository::FILTER_BY_COMPANY => $company
                ];

                return $repo->findAllQuery($filters);
            },
        ]);

        $this->addRecurrenceFields($builder);

        $builder->add('validUntil', DateType::class, [
            'label' => 'fields.valid_until',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
        ]);

        $builder->add('rows', DocumentRowCollectionType::class);

        $builder->add('attachments', CollectionUploaderType::class, [
            'label' => 'fields.attachments',
            'required' => false,
            'entry_type' => AttachmentType::class,
            'endpoint' => 'attachable',
            'entry_options' => [
                'data_class' => DocumentAttachment::class,
            ]
        ]);

        $builder->add('direction', ChoiceType::class, [
            'choices' => $this->buildDirections(),
            'label' => 'fields.direction',
            'expanded' => true,
            'attr' => [
                'class' => 'btn-group',
                'data-toggle' => 'buttons',
            ],
        ]);
        $builder->add('selfInvoice', CheckboxType::class, [
            'label' => 'fields.self_invoice',
            'required' => false,
        ]);

        $this->addLinkedOrderFields($builder);
        $this->addLinkedCreditNoteFields($builder);
        $this->addLinkedInvoiceFields($builder);

        $builder->add('save', SubmitType::class, [
            'label' => 'actions.save',
        ]);

        $builder->add('saveAndClose', SubmitType::class, [
            'label' => 'actions.save_and_close',
            'button_class' => 'btn btn-default',
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Document $data */
            $data = $event->getData();

            /** @var Form $form */
            $form = $event->getForm();

            if ($data) {
                if ($data->hasCompanyLogoUrl()) {
                    $form->add('deleteCompanyLogo', CheckboxType::class, [
                        'label' => 'actions.delete_logo',
                        'required' => false,
                    ]);
                }
            }
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data && !$data->getId()) {
                $form->add('addNewCustomer', CheckboxType::class, [
                    'label' => false,
                ]);
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Document $data */
            $data = $event->getData();

            $data->calculateTotals();
        });
    }

    public function getBlockPrefix()
    {
        return 'document';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'company',
            'available_locales',
        ]);

        $resolver->setDefaults([
            'data_class' => Document::class,
            'validation_groups' => function (FormInterface $form) {
                if ($form->get('save')->isClicked() || $form->get('saveAndClose')->isClicked()) {
                    return 'Default';
                }

                return null;
            },
        ]);
    }

    private function buildTypes(Company $company)
    {
        $types = [];
        foreach ($company->getDocumentTypes() as $type) {
            $types['document.type.' . $type] = $type;
        };

        return $types;
    }

    private function buildDirections()
    {
        return [
            'document.direction.incoming' => Document::INCOMING,
            'document.direction.outgoing' => Document::OUTGOING,
        ];
    }

    private function buildLocales($availableLocales)
    {
        $locales = [];
        foreach ($availableLocales as $locale) {
            $locales['locale.' . $locale] = $locale;
        }

        return $locales;
    }

    private function addLinkedOrderFields(FormBuilderInterface $builder)
    {
        $this->addLinkedDocument('linkedOrder', 'linked_order', 'order', $builder);
    }

    private function addLinkedCreditNoteFields(FormBuilderInterface $builder)
    {
        $this->addLinkedDocument('linkedCreditNote', 'linked_credit_note', 'credit-note', $builder);
    }

    private function addLinkedInvoiceFields(FormBuilderInterface $builder)
    {
        $this->addLinkedDocument('linkedInvoice', 'linked_invoice', 'invoice', $builder);
    }

    private function addRecurrenceFields(FormBuilderInterface $builder)
    {
        $builder->add('recurrenceTitle', DocumentLinkType::class, [
            'label' => 'fields.recurrence',
            'required' => false,
            'linked_to' => 'recurrence',
            'type' => 'recurrence'
        ]);

        $builder->add('recurrence', EntityTextType::class, [
            'label' => false,
            'required' => false,
            'class' => Recurrence::class,
            'invalid_message' => 'error.invalid_recurrence',
        ]);

    }

    private function addLinkedDocument($field, $label, $type, FormBuilderInterface $builder)
    {
        $builder->add($field . 'Title', DocumentLinkType::class, [
            'label' => 'fields.' . $label,
            'required' => false,
            'linked_to' => $field,
            'type' => $type
        ]);

        $builder->add($field, EntityTextType::class, [
            'label' => false,
            'required' => false,
            'class' => Document::class,
            'invalid_message' => 'error.invalid_document',
        ]);
    }
}
