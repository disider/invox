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

use App\Entity\Account;
use App\Entity\Company;
use App\Entity\PettyCashNote;
use App\Entity\PettyCashNoteAttachment;
use App\Form\Type\AttachmentType;
use App\Form\Type\CollectionUploaderType;
use App\Form\Type\InvoiceCollectionType;
use App\Form\Type\LocalizedNumberType;
use App\Repository\AccountRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PettyCashNoteForm extends AbstractType
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

        $builder->add('ref', TextType::class, [
            'label' => 'fields.ref',
            'attr' => [
                'placeholder' => 'fields.placeholder.ref',
            ],
        ]);

        $builder->add('recordedAt', DateType::class, [
            'label' => 'fields.recorded_at',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
        ]);

        $builder->add('amount', LocalizedNumberType::class, ['label' => 'fields.amount']);

        $builder->add('description', TextareaType::class, [
            'label' => 'fields.description',
            'required' => false,
        ]);

        $builder->add('invoices', InvoiceCollectionType::class);

        $builder->add('attachments', CollectionUploaderType::class, [
            'label' => 'fields.attachments',
            'required' => false,
            'entry_type' => AttachmentType::class,
            'endpoint' => 'attachable',
            'entry_options' => [
                'data_class' => PettyCashNoteAttachment::class,
            ]
        ]);

        $builder->add('save', SubmitType::class, ['label' => 'actions.save']);
        $builder->add('saveAndClose', SubmitType::class, [
            'label' => 'actions.save_and_close',
            'button_class' => 'btn btn-default',
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'onPostSetData']);
    }

    public function getBlockPrefix()
    {
        return 'pettyCashNote';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');

        $resolver->setDefaults([
            'data_class' => PettyCashNote::class,
            'allow_extra_fields' => true,
            'validation_groups' => function (FormInterface $form) {
                if ($form->get('save')->isClicked() || $form->get('saveAndClose')->isClicked()) {
                    return 'Default';
                }

                return 'HandleInvoices';
            }
        ]);
    }

    public function onPostSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $form->add('accountFrom', EntityType::class, [
            'class' => Account::class,
            'label' => 'fields.account_from',
            'placeholder' => 'petty_cash_note.no_transfer',
            'query_builder' => function (AccountRepository $repo) use ($data) {
                return $repo->findByCompanyQuery($data->getCompany());
            },
            'required' => false,
        ]);

        $form->add('accountTo', EntityType::class, [
            'class' => Account::class,
            'label' => 'fields.account_to',
            'placeholder' => 'petty_cash_note.no_transfer',
            'query_builder' => function (AccountRepository $repo) use ($data) {
                return $repo->findByCompanyQuery($data->getCompany());
            },
            'required' => false,
        ]);
    }
}
