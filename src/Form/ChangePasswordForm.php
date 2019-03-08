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

use App\Validator\Constraints\CurrentUserPassword;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordForm extends BaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'currentPassword',
            PasswordType::class,
            [
                'label' => 'fields.current_password',
                'mapped' => false,
                'constraints' => new CurrentUserPassword(['groups' => 'change_password']),
            ]
        );

        $builder->add(
            'newPassword',
            PasswordType::class,
            [
                'label' => 'fields.new_password',
                'property_path' => 'plainPassword',
            ]
        );

        $builder->add('save', SubmitType::class, ['label' => 'actions.save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'validation_groups' => ['change_password'],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'changePassword';
    }
}
