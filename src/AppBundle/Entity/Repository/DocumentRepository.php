<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Company;
use AppBundle\Entity\Document;
use AppBundle\Entity\InvoicePerNote;
use AppBundle\Model\DocumentType;
use Doctrine\ORM\QueryBuilder;

class DocumentRepository extends ProtocolRepository
{
    const ROOT_ALIAS = 'document';

    const FILTER_BY_TYPE = 'filter_by_type';
    const FILTER_BY_DIRECTION = 'filter_by_direction';
    const FILTER_BY_STATUS = 'filter_by_status';

    public function findCostCenters($filters)
    {
        $records = $this->findAllQuery($filters)
            ->select('document.costCenter as costCenter')
            ->andWhere('document.costCenter IS NOT NULL')
            ->distinct()
            ->getQuery()
            ->getScalarResult();

        $costCenters = [];
        foreach ($records as $record) {
            $costCenters[] = $record['costCenter'];
        }

        return $costCenters;
    }

    public function countAvailableInvoices(Company $company)
    {
        $records = $this->findAvailableInvoicesQuery($company)
            ->getQuery()
            ->execute();

        return count($records);
    }

    public function findExpiredInvoices(Company $company, $direction)
    {
        $today = new \DateTime('today midnight');

        return $this->findInvoicesByCompanyQuery($company)
            ->andWhere('document.validUntil <= :today')
            ->andWhere('document.status = :status')
            ->andWhere('document.direction = :direction')
            ->setParameter('today', $today)
            ->setParameter('status', Document::UNPAID)
            ->setParameter('direction', $direction)
            ->getQuery()->execute();
    }

    public function getGrossTotal(QueryBuilder $query)
    {
        return $query->select('SUM(document.grossTotal)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPaidTotal(QueryBuilder $query)
    {
        return $query->select('SUM(note.amount)')
            ->leftJoin('document.pettyCashNotes', 'note')
            ->getQuery()
            ->getSingleScalarResult();
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }

    public function findAvailableInvoicesQuery(Company $company)
    {
        return $this->findInvoicesByCompanyQuery($company)
            ->having('SUM(note.amount) < document.grossTotal OR SUM(note.amount) IS NULL');
    }

    public function findAvailableInvoicesByNoteQuery(Company $company, InvoicePerNote $invoicePerNote)
    {
        return $this->findInvoicesByCompanyQuery($company)
            ->andWhere('note = :note')
            ->setParameter('note', $invoicePerNote);
    }

    public function findInvoicesByCompanyQuery(Company $company)
    {
        return $this->createQueryBuilder('document')
            ->leftJoin('document.company', 'company')
            ->leftJoin('document.pettyCashNotes', 'note')
            ->leftJoin('document.rows', 'row')
            ->andWhere('company = :company')
            ->andWhere('document.type = :type')
            ->groupBy('document.id')
            ->setParameter('company', $company)
            ->setParameter('type', DocumentType::INVOICE);
    }

    public function search($term, Company $company, array $filters = [])
    {
        $qb = $this->createQueryBuilder('document')
            ->where('(document.ref LIKE :term OR document.customerName LIKE :term OR document.title LIKE :term)')
            ->andWhere('document.company = :company')
            ->setParameter('term', '%' . $term . '%')
            ->setParameter('company', $company);

        $qb = $this->applyFilters($qb, $filters);

        return $qb
            ->getQuery()
            ->execute();
    }

    /** @return QueryBuilder */
    protected function filterByYear(QueryBuilder $qb, $alias, $year)
    {
        return $qb->andWhere($alias . '.year = :year')
            ->setParameter('year', $year);
    }

    protected function applyFilters($qb, array $filters)
    {
        parent::applyFilters($qb, $filters);

        if (array_key_exists(self::FILTER_BY_TYPE, $filters)) {
            $qb = $this->filterByType($qb, $filters[self::FILTER_BY_TYPE]);
        }

        if (array_key_exists(self::FILTER_BY_STATUS, $filters)) {
            $qb = $this->filterByStatus($qb, $filters[self::FILTER_BY_STATUS]);
        }

        if (array_key_exists(self::FILTER_BY_DIRECTION, $filters)) {
            $qb = $this->filterByDirection($qb, $filters[self::FILTER_BY_DIRECTION]);
        }

        return $qb;
    }

    private function filterByType(QueryBuilder $qb, $type)
    {
        $type = str_replace('-', '_', $type);

        $alias = $this->getRootAlias();
        $qb
            ->andWhere($alias . '.type = :type')
            ->setParameter('type', $type);

        return $qb;
    }

    private function filterByStatus(QueryBuilder $qb, $status)
    {
        $alias = $this->getRootAlias();
        $qb
            ->andWhere($alias . '.status = :status')
            ->setParameter('status', $status);

        return $qb;
    }

    private function filterByDirection(QueryBuilder $qb, $direction)
    {
        $alias = $this->getRootAlias();
        $qb
            ->andWhere($alias . '.direction = :direction')
            ->setParameter('direction', $direction);

        return $qb;
    }
}
