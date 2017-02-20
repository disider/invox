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

use AppBundle\Entity\DocumentTemplatePerCompany;
use AppBundle\Entity\User;
use AppBundle\Form\Type\ColorType;
use AppBundle\Form\Type\LocalizedNumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentTemplatePerCompanyForm extends DocumentTemplateForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $user = $options['user'];

        if ($user->isSuperadmin()) {
            $builder->add('company', EntityType::class, [
                'class' => 'AppBundle:Company',
                'label' => 'fields.company',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->setDefaults([
            'data_class' => DocumentTemplatePerCompany::class
        ]);
    }
}
