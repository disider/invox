<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Filter;

use AppBundle\Entity\PettyCashNote;
use AppBundle\Entity\Recurrence;
use Doctrine\ORM\Query\Expr;
use Doctrine\DBAL\Connection;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RecurrenceFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('direction', ChoiceFilterType::class, [
            'choices' => [
                'recurrence.direction.outgoing' => Recurrence::OUTGOING,
                'recurrence.direction.incoming' => Recurrence::INCOMING
            ],
            'label' => 'fields.direction',
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $qb = $filterQuery->getQueryBuilder();

                $qb->andWhere($filterQuery->getRootAlias() . '.direction = :direction')
                    ->setParameter('direction', $values['value']);

                return $filterQuery;
            },
        ]);

        $builder->add('content', TextFilterType::class, [
            'label' => 'fields.content',
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $qb = $filterQuery->getQueryBuilder();
                $qb->andWhere($filterQuery->getRootAlias() . '.content LIKE :content')
                    ->setParameter('content', '%' . $values['value'] . '%');

                return $filterQuery;
            },
        ]);

        $builder->add('customer', TextFilterType::class, [
            'label' => 'fields.customer',
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $qb = $filterQuery->getQueryBuilder();
                $qb->leftJoin($filterQuery->getRootAlias() . '.customer', 'customer')
                    ->andWhere('customer.name LIKE :name')
                    ->setParameter('name', '%' . $values['value'] . '%');

                return $filterQuery;
            },
        ]);


        $this->addDateRangeType($builder, 'startAt', 'fields.start_at');

        $this->addDateRangeType($builder, 'nextDueDate', 'fields.next_due_date');


        $builder->add('filter', SubmitType::class, [
            'label' => 'actions.filter',
            'button_class' => 'btn btn-primary btn-sm',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'recurrenceFilter';
    }


}
