<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\DocumentRow;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentRowCollectionType extends LocalizedType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'entry_type' => DocumentRowType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'error_bubbling' => false,
            'required' => false,
            'prototype' => true,
            'prototype_data' => new DocumentRow(),
            'delete_empty' => true,
            'attr' => [
                'class' => 'collection',
            ],
            'entry_options' => [
                'label' => false,
            ]
        ]);
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'documentRows';
    }

}
