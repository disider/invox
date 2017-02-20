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

use AppBundle\Entity\PettyCashNote;
use Doctrine\ORM\Query\Expr;
use Doctrine\DBAL\Connection;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PettyCashNoteFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', ChoiceFilterType::class, [
            'choices' => $this->buildTypes(),
            'label' => 'fields.type',
            'multiple' => true,
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                $value = $values['value'];
                if (empty($value)) {
                    return null;
                }
                $paramName = sprintf('p_%s', str_replace('.', '_', $field));
                $expression = $filterQuery->getExpr()->in($field, ':' . $paramName);
                $parameters = [$paramName => [$values['value'], Connection::PARAM_STR_ARRAY]];

                return $filterQuery->createCondition($expression, $parameters);
            },
            'attr' => [
                'class' => 'selectize'
            ],
        ]);

        $builder->add('ref', TextFilterType::class, [
            'label' => 'fields.ref',
        ]);

        $builder->add('customer', TextFilterType::class, [
            'label' => 'fields.customer',
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $qb = $filterQuery->getQueryBuilder();
                $qb
                    ->leftJoin($filterQuery->getRootAlias() . '.invoices', 'invoicePerNote')
                    ->leftJoin('invoicePerNote.invoice', 'invoice')
                    ->leftJoin('invoice.linkedCustomer', 'customer')
                    ->andWhere('customer.name LIKE :name')
                    ->orWhere('invoice.customerName LIKE :name')
                    ->setParameter('name', '%' . $values['value'] . '%');

                return $filterQuery;
            },
        ]);

        $builder->add('account', TextFilterType::class, [
            'label' => 'fields.account',
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $qb = $filterQuery->getQueryBuilder();
                $qb
                    ->leftJoin($filterQuery->getRootAlias() . '.accountFrom', 'accountFrom')
                    ->leftJoin($filterQuery->getRootAlias() . '.accountTo', 'accountTo')
                    ->andWhere('accountFrom.name LIKE :name OR accountTo.name LIKE :name')
                    ->setParameter('name', '%' . $values['value'] . '%');

                return $filterQuery;
            },
        ]);

        $this->addDateRangeType($builder, 'recordedAt', 'fields.recorded_at');

        $builder->add('description', TextFilterType::class, [
            'label' => 'fields.description',
            'condition_pattern' => FilterOperands::STRING_CONTAINS,
        ]);

        $builder->add('filter', SubmitType::class, [
            'label' => 'actions.filter',
            'button_class' => 'btn btn-primary btn-sm',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'pettyCashNoteFilter';
    }

    private function buildTypes()
    {
        return [
            'petty_cash_note.type.all_types' => null,
            'petty_cash_note.type.transfer' => PettyCashNote::TRANSFER,
            'petty_cash_note.type.income' => PettyCashNote::INCOME,
            'petty_cash_note.type.outcome' => PettyCashNote::OUTCOME,
        ];
    }

}
