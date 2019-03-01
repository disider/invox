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
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options['type'];
        $builder->add('ref', TextFilterType::class, [
            'label' => 'fields.ref',
            'condition_pattern' => FilterOperands::STRING_CONTAINS,
        ]);

        $this->addDateRangeType($builder, 'issuedAt', 'fields.issued_at');
        $this->addDateRangeType($builder, 'validUntil', 'fields.valid_until');

        if ($type == DocumentType::INVOICE) {
            $builder->add('direction', ChoiceFilterType::class, [
                'choices' => $this->buildDirections(),
                'label' => 'fields.direction',
            ]);
        }

        if ($type != DocumentType::QUOTE) {
            $builder->add('status', ChoiceFilterType::class, [
                'choices' => $this->buildStatus(),
                'label' => 'fields.status',
            ]);

            $builder->add('costCenters', TextFilterType::class, [
                'label' => 'fields.cost_centers',
                'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                    if (empty($values['value'])) {
                        return null;
                    }

                    $qb = $filterQuery->getQueryBuilder();
                    $qb
                        ->leftJoin($filterQuery->getRootAlias() . '.costCenters', 'costCenter')
                        ->andWhere('costCenter.name LIKE :name')
                        ->setParameter('name', '%' . $values['value'] . '%');

                    return $filterQuery;
                },
            ]);
        }

        $builder->add('customer', TextFilterType::class, [
            'label' => 'fields.customer',
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $qb = $filterQuery->getQueryBuilder();
                $qb
                    ->leftJoin($filterQuery->getRootAlias() . '.linkedCustomer', 'customer')
                    ->andWhere('customer.name LIKE :name or ' . $filterQuery->getRootAlias() . '.customerName LIKE :name')
                    ->setParameter('name', '%' . $values['value'] . '%');

                return $filterQuery;
            },
        ]);

        $builder->add('filter', SubmitType::class, [
            'label' => 'actions.filter',
            'button_class' => 'btn btn-primary btn-sm',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'documentFilter';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('type');
    }

    private function buildDirections()
    {
        return [
            'document.direction.incoming' => Document::INCOMING,
            'document.direction.outgoing' => Document::OUTGOING,
        ];
    }

    private function buildStatus()
    {
        return [
            'document.status.unpaid' => Document::UNPAID,
            'document.status.paid' => Document::PAID,
        ];
    }
}
