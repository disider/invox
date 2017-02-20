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

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordForm extends BaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', PasswordType::class, [
            'label' => 'fields.password',
            'property_path' => 'plainPassword',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'resetPassword';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'validation_groups' => ['reset_password'],
            ]
        );
    }
}
