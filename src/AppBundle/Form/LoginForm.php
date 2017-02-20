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

use AppBundle\EventListener\FormAuthenticationListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $authenticationListener = $options['authentication_listener'];

        $builder->add('_username', TextType::class, ['label' => 'fields.username_or_email']);
        $builder->add('_password', PasswordType::class, ['label' => 'fields.password']);
        $builder->add('_remember_me', CheckboxType::class, ['label' => 'fields.remember_me', 'required' => false]);
        $builder->add('_target_path', HiddenType::class, ['data' => $options['target_path']]);

        $builder->add('login', SubmitType::class, ['label' => 'fields.login']);

        $builder->addEventSubscriber($authenticationListener);
    }

    public function getBlockPrefix()
    {
        return;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('authentication_listener');

        $resolver->setDefaults(
            [
                'csrf_field_name' => '_csrf_token',
                'intention' => 'authenticate',
                'target_path' => '',
            ]
        );
    }
}
