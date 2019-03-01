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

use AppBundle\Entity\InvoicePerNote;
use AppBundle\Form\Type\LocalizedNumberType;
use AppBundle\Repository\DocumentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoicePerNoteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', HiddenType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            /** @var InvoicePerNote $data */
            $data = $event->getData();
            $form = $event->getForm();

            if ($data && $data->getId() != null) {
                $form->add('invoice', EntityType::class, [
                    'class' => 'AppBundle:Document',
                    'label' => 'fields.invoice',
                    'disabled' => true,
                ]);
            } else {
                $form->add('invoice', EntityType::class, [
                    'class' => 'AppBundle:Document',
                    'label' => 'fields.invoice',
                    'query_builder' => function (DocumentRepository $repo) use ($options) {
                        return $repo->findAvailableInvoicesQuery($options['company']);
                    },
                ]);
            }
        });

        $builder->add('amount', LocalizedNumberType::class, [
            'label' => 'fields.amount',
        ]);

        $builder->add('remove', SubmitType::class, [
            'label' => false,
            'button_class' => 'link',
            'attr' => [
                'icon' => 'trash',
                'title' => 'actions.remove',
            ]
        ]);
    }

    public function getBlockPrefix()
    {
        return 'invoicePerNote';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvoicePerNote::class,
            'allow_extra_fields' => true,
            'company' => null,
        ]);
    }
}
