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

use App\Entity\Medium;
use App\Form\Type\UploaderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediumForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'fileName',
            TextType::class,
            [
                'label' => 'fields.file_name',
            ]
        );

        $builder->add(
            'fileUrl',
            UploaderType::class,
            [
                'label' => false,
                'endpoint' => 'medium',
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Medium::class,
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'medium';
    }
}

