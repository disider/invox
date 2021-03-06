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

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class PageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'translations',
            TranslationsType::class,
            [
                'label' => false,
                'constraints' => new Valid()
            ]
        );

        $builder->add(
            'save',
            SubmitType::class,
            [
                'label' => 'actions.save',
            ]
        );

        $builder->add(
            'saveAndClose',
            SubmitType::class,
            [
                'label' => 'actions.save_and_close',
                'button_class' => 'btn btn-default',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'page';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Page::class,
                'constraints' => new Valid(),
            ]
        );
    }
}
