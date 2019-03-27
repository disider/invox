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

use App\Entity\Customer;
use App\Entity\WorkingNote;
use App\Form\Type\DocumentLinkType;
use App\Form\Type\EntityTextType;
use App\Form\Type\ParagraphCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkingNoteForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null, ['label' => 'fields.title']);
        $builder->add('code', null, ['label' => 'fields.code']);

        $builder->add(
            'customer',
            EntityTextType::class,
            [
                'label' => false,
                'class' => Customer::class,
                'required' => false,
                'attr' => [
                    'class' => 'hidden js-customer',
                ],
                'invalid_message' => 'error.invalid_customer',
            ]
        );

        $builder->add(
            'customerName',
            DocumentLinkType::class,
            [
                'label' => 'fields.name',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'fields.autocomplete_customer',
                    'class' => 'js-customer-name',
                    'data-search-url' => $options['search-url'],
                ],
                'linked_to' => 'customer',
                'type' => 'customer',
            ]
        );

        $builder->add('paragraphs', ParagraphCollectionType::class);

        $builder->add('save', SubmitType::class, ['label' => 'actions.save',]);
        $builder->add(
            'saveAndClose',
            SubmitType::class,
            [
                'label' => 'actions.save_and_close',
            ]
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                /** @var WorkingNote $data */
                $data = $event->getData();
                $form = $event->getForm();

                if ($data->getCustomer()) {
                    $form->get('customerName')->setData($data->getCustomer()->getName());
                }
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => WorkingNote::class,
            ]
        );
        $resolver->setRequired(['search-url']);
    }

    public function getBlockPrefix()
    {
        return 'workingNote';
    }
}

