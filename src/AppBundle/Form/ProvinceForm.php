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

use AppBundle\Entity\Province;
use AppBundle\Form\Type\CountryEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProvinceForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['label' => 'fields.name']);
        $builder->add('code', TextType::class, ['label' => 'fields.code']);
        $builder->add('country', CountryEntityType::class);

        $builder->add('save', SubmitType::class, ['label' => 'actions.save']);
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
        return 'province';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Province::class,
        ]);
    }

}
