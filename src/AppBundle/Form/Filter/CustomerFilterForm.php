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
use AppBundle\Entity\PettyCashNote;
use AppBundle\Model\DocumentType;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Doctrine\DBAL\Connection;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerFilterForm extends BaseFilterForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextFilterType::class, [
            'label' => 'fields.name',
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $qb = $filterQuery->getQueryBuilder();
                $qb->andWhere(sprintf('%s LIKE :%s', $field, 'name'))
                    ->setParameter('name', '%' . $values['value'] . '%');

                return $filterQuery;
            },
        ]);


        $builder->add('type', ChoiceFilterType::class, [
            'choices' => [
                'customer.type.prospect' => DocumentType::QUOTE,
                'customer.type.customer' => Document::OUTGOING,
                'customer.type.supplier' => Document::INCOMING,
            ],
            'label' => 'fields.type',
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $qb = $filterQuery->getQueryBuilder();

                $qb->leftJoin($filterQuery->getRootAlias()  . '.documents', 'd')
                    ->andWhere('(d.direction = :direction and d.type = :type)')
                    ->setParameter('direction', $values['value'] == DocumentType::QUOTE ? Document::NO_DIRECTION : $values['value'] )
                    ->setParameter('type', $values['value'] == DocumentType::QUOTE ? DocumentType::QUOTE : DocumentType::INVOICE );

                return $filterQuery;
            },
        ]);

        $builder->add('vatNumber', TextFilterType::class, [
            'label' => 'fields.vat_number',
        ]);

        $builder->add('fiscalCode', TextFilterType::class, [
            'label' => 'fields.fiscal_code',
        ]);

        $builder->add('filter', SubmitType::class, [
            'label' => 'actions.filter',
            'button_class' => 'btn btn-primary btn-sm',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'customerFilter';
    }
    
}
