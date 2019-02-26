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

use AppBundle\Entity\User;
use AppBundle\Form\Type\PlainType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        if ($user->canManageMultipleCompanies()) {
            $builder->add('ownedCompanies', EntityType::class, [
                'label' => 'fields.owned_companies',
                'class' => 'AppBundle:Company',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'selectize'
                ],
            ]);

            $builder->add('accountedCompanies', EntityType::class, [
                'label' => 'fields.accounted_companies',
                'class' => 'AppBundle:Company',
                'multiple' => true,
                'by_reference' => false,
                'required' => false,
                'attr' => [
                    'class' => 'selectize'
                ],
            ]);

            $builder->add('managedCompanies', EntityType::class, [
                'label' => 'fields.managed_companies',
                'class' => 'AppBundle:Company',
                'multiple' => true,
                'placeholder' => '',
                'by_reference' => false,
                'required' => false,
                'attr' => [
                    'class' => 'selectize'
                ],
            ]);

            $builder->add('marketedCompanies', EntityType::class, [
                'label' => 'fields.marketed_companies',
                'class' => 'AppBundle:Company',
                'multiple' => true,
                'placeholder' => '',
                'by_reference' => false,
                'required' => false,
                'attr' => [
                    'class' => 'selectize'
                ],
            ]);

            $builder->add('enabled', CheckboxType::class, [
                'label' => 'fields.enabled',
                'required' => false,
            ]);
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($user) {
                /** @var User $formData */
                $formData = $event->getData();
                $form = $event->getForm();

                if ($user->isSuperadmin()) {
                    $form->add('isSuperAdmin', CheckboxType::class, [
                        'label' => 'fields.is_super_admin',
                        'required' => false,
                        'mapped' => false,
                        'value' => $formData->isSuperadmin()
                    ]);
                }

                if ($user->isSuperadmin() || $user->isManagingMultipleCompanies()) {
                    $form->add('email', TextType::class, ['label' => 'fields.email']);
                }
                else {
                    $form->add('email', PlainType::class, [
                        'label' => 'fields.email',
                    ]);
                }

                if (!$formData || !$user->isSameAs($formData)) {
                    $form->add('password', PasswordType::class, [
                        'label' => 'fields.password',
                        'property_path' => 'plainPassword',
                        'required' => $formData->getId() ? false : true,
                    ]);
                }
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($user) {
                /** @var User $formData */
                $formData = $event->getData();
                $form = $event->getForm();

                if ($form['isSuperAdmin']->getData()) {
                    $formData->setRoles([User::ROLE_SUPER_ADMIN, User::ROLE_ALLOWED_TO_SWITCH]);
                }
                else {
                    $formData->setRoles([]);
                }
            }
        );

        $builder->add('save', SubmitType::class, ['label' => 'actions.save']);
        $builder->add('saveAndClose', SubmitType::class, [
            'label' => 'actions.save_and_close',
            'button_class' => 'btn btn-default',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'user';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');

        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => function (FormInterface $form) {
                /** @var User $user */
                $user = $form->getData();

                return ($user->getId() && !$user->getPlainPassword()) ? 'update' : 'registration';
            },
        ]);
    }

    protected function buildRoles($roles)
    {
        $records = [];
        foreach ($roles as $role) {
            $records[$role] = 'role.' . substr(strtolower($role), 5);
        }
        return $records;
    }
}
