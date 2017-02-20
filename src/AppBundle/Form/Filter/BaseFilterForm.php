<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Filter;

use AppBundle\Entity\Document;
use AppBundle\Model\DocumentType;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Query\Expr;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseFilterForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'   => false,
            'validation_groups' => ['filtering']
        ]);
    }

    protected function getFilterFunction()
    {
        return function (ORMQuery $queryBuilder, $expr, $field) {
            if (!empty($field['value'])) {
                $value = $field['value'];

                if ($value instanceof \DateTime)
                    $queryBuilder->getQueryBuilder()
                        ->andWhere($expr . ' =  :value')
                        ->setParameter('value', $value);
                else
                    $queryBuilder->getQueryBuilder()
                        ->andWhere($expr . ' LIKE  :value')
                        ->setParameter('value', '%' . $value . '%');
            }
        };
    }

    protected function addDateRangeType(FormBuilderInterface $builder, $field, $label)
    {
        $builder->add($field, DateRangeFilterType::class, [
            'label' => $label,
            'left_date_options' => [
                'label' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'datepicker',
                    'input_group' => [
                        'prepend' => 'fields.from_date',
                    ],
                ],
            ],
            'right_date_options' => [
                'label' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'class' => 'datepicker',
                    'input_group' => [
                        'prepend' => 'fields.to_date',
                    ],
                ],
            ],
        ]);
    }

}
