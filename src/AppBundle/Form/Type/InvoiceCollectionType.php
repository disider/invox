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

use AppBundle\Entity\Manager\CompanyManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceCollectionType extends AbstractType
{
    /**
     * @var CompanyManager
     */
    private $companyManager;

    public function __construct(CompanyManager $companyManager)
    {
        $this->companyManager = $companyManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'error_bubbling' => false,
            'required' => false,
            'entry_type' => InvoicePerNoteType::class,
            'prototype' => true,
            'delete_empty' => true,
            'attr' => [
                'class' => 'collection',
            ],
            'entry_options' => [
                'label' => false,
                'company' => $this->companyManager->getCurrent(),
            ],
        ]);
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'invoices';
    }

}
