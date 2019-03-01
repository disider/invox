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

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactUsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'fields.email',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'error.empty_email']),
                    new Email(['message' => 'error.invalid_email'])
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'fields.subject',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'error.empty_subject']),
                ],
            ])
            ->add('body', TextareaType::class, [
                'label' => 'fields.body',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'error.empty_body']),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'actions.send',
            ]);

        if (!$options['debug']) {
            $builder->add('recaptcha', EWZRecaptchaType::class, [
                'label' => false,
                'mapped' => false,
                'constraints' => [
                    new RecaptchaTrue(['message' => 'contact_us.invalid_recaptcha'])
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'debug' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'contact_us';
    }
}