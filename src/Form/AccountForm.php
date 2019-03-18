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
use App\Entity\AccountType;
use App\Entity\Company;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder->add(
            'type',
            ChoiceType::class,
            [
                'choices' => $this->buildTypes(),
                'label' => 'fields.type',
            ]
        );
        $builder->add('name', TextType::class, ['label' => 'fields.name']);
        $builder->add('initialAmount', NumberType::class, ['label' => 'fields.initial_amount']);
        $builder->add(
            'iban',
            TextType::class,
            [
                'label' => 'fields.iban',
                'required' => false,
            ]
        );

        if ($user->isSuperadmin()) {
            $builder->add(
                'company',
                EntityType::class,
                [
                    'class' => Company::class,
                    'label' => 'fields.company',
                ]
            );
        }

        $builder->add('save', SubmitType::class, ['label' => 'actions.save']);
        $builder->add(
            'saveAndClose',
            SubmitType::class,
            [
                'label' => 'actions.save_and_close',
                'button_class' => 'btn btn-default',
            ]
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if ($data && $data->getId()) {
                    $form->add(
                        'currentAmount',
                        NumberType::class,
                        [
                            'label' => 'fields.current_amount',
                        ]
                    );
                }
            }
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                /** @var Account $data */
                $data = $event->getData();

                if (is_null($data->getId())) {
                    $data->setCurrentAmount($data->getInitialAmount());
                }
            }
        );

    }

    public function getBlockPrefix()
    {
        return 'account';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->setDefaults(
            [
                'data_class' => Account::class,
            ]
        );
    }

    private function buildTypes()
    {
        $types = [];
        foreach (AccountType::getTypes() as $type) {
            $types['account.type.'.$type] = $type;
        };

        return $types;
    }
}
