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

use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentAttachment;
use AppBundle\Entity\PaymentType;
use AppBundle\Entity\Recurrence;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Form\Type\AttachmentType;
use AppBundle\Form\Type\CollectionUploaderType;
use AppBundle\Form\Type\CountryEntityType;
use AppBundle\Form\Type\DiscountPercentToggleType;
use AppBundle\Form\Type\DocumentLinkType;
use AppBundle\Form\Type\DocumentRowCollectionType;
use AppBundle\Form\Type\EntityTextType;
use AppBundle\Form\Type\LocalizedNumberType;
use AppBundle\Form\Type\TagType;
use AppBundle\Form\Type\TextEditorType;
use AppBundle\Form\Type\WeekdaysType;
use AppBundle\Model\DocumentType;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecurrenceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('direction', ChoiceType::class, [
            'choices' => $this->buildDirections(),
            'label' => 'fields.direction',
            'expanded' => true,
            'attr' => [
                'class' => 'form-group btn-group',
                'data-toggle' => 'buttons',
            ],
        ]);

        $builder->add('amount', LocalizedNumberType::class, ['label' => 'fields.amount']);

        $builder->add('repeats', ChoiceType::class, [
            'choices' => $this->buildRepeats(),
            'label' => false,
            'expanded' => true,
            'attr' => [
                'class' => 'form-group btn-group',
                'data-toggle' => 'buttons',
            ],
        ]);

        $builder->add('repeatEvery', NumberType::class, [
            'label' => 'fields.repeat_every',
        ]);

//        $builder->add('repeatFor', ChoiceType::class, [
//            'choices' => $this->buildRepeatFor(),
//            'required' => false,
//            'mapped' => false,
//            'label' => 'fields.repeat_for',
//        ]);

        $builder->add('repeatDays', WeekdaysType::class, [
            'required' => false,
            'mapped' => false,
            'label' => 'fields.repeat_days',
            'attr' => [
                'inline' => true
            ]
        ]);

        $builder->add('content', TextType::class, [
            'label' => 'fields.content',
            'required' => false,
            'attr' => [
                'placeholder' => 'fields.placeholder.content',
            ],
        ]);

        $builder->add('startAt', DateType::class, [
            'label' => 'fields.start_at',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'datepicker text-center'
            ]
        ]);

        $builder->add('customer', EntityTextType::class, [
            'label' => false,
            'class' => Customer::class,
            'attr' => [
                'class' => 'hidden',
            ],
            'invalid_message' => 'error.invalid_customer',
        ]);

        $builder->add('customerName', DocumentLinkType::class, [
            'label' => 'fields.name',
            'mapped' => false,
            'attr' => [
                'placeholder' => 'fields.autocomplete_customer',
            ],
            'linked_to' => 'customer',
            'type' => 'customer'
        ]);

        $builder->add('occurrences', NumberType::class, [
            'label' => 'fields.occurrences',
            'required' => false,
        ]);

        $builder->add('endAt', DateType::class, [
            'label' => 'fields.end_at',
            'required' => false,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'datepicker text-center'
            ]

        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'actions.save',
        ]);

        $builder->add('saveAndClose', SubmitType::class, [
            'label' => 'actions.save_and_close',
            'button_class' => 'btn btn-default',
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /** @var Recurrence $data */
            $data = $event->getData();
            $form = $event->getForm();

            if ($data->getCustomer())
                $form->get('customerName')->setData($data->getCustomer()->getName());

            if ($data->getRepeats() == Recurrence::EVERY_WEEK)
                $form->get('repeatDays')->setData($data->getRepeatOn());
//            else if ($data->getRepeats() == Recurrence::EVERY_MONTH)
//                $form->get('repeatFor')->setData($data->getRepeatOn());
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var Recurrence $data */
            $data = $event->getData();
            $form = $event->getForm();

            if ($data->getRepeats() == Recurrence::EVERY_WEEK)
                $data->setRepeatOn($form->get('repeatDays')->getData());
//            else if ($data->getRepeats() == Recurrence::EVERY_MONTH)
//                $data->setRepeatOn($form->get('repeatFor')->getData());
            else
                $data->setRepeatOn('');
        });

    }

    public function getBlockPrefix()
    {
        return 'recurrence';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recurrence::class,
            'validation_groups' => function (FormInterface $form) {
                if ($form->get('save')->isClicked() || $form->get('saveAndClose')->isClicked()) {
                    return 'Default';
                }

                return null;
            },
        ]);
    }

    private function buildDirections()
    {
        return [
            'recurrence.direction.incoming' => Recurrence::INCOMING,
            'recurrence.direction.outgoing' => Recurrence::OUTGOING,
        ];
    }

    private function buildRepeats()
    {
        return [
            'recurrence.repeat.everyday' => Recurrence::EVERYDAY,
            'recurrence.repeat.every_week' => Recurrence::EVERY_WEEK,
            'recurrence.repeat.every_month' => Recurrence::EVERY_MONTH,
            'recurrence.repeat.every_year' => Recurrence::EVERY_YEAR,
        ];
    }

    private function buildRepeatFor()
    {
        return [
            'recurrence.repeat_for.day_of_the_week' => Recurrence::DAY_OF_THE_WEEK,
            'recurrence.repeat_for.day_of_the_month' => Recurrence::DAY_OF_THE_MONTH,
        ];
    }

}
