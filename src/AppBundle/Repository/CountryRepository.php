<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Country;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CountryRepository extends AbstractRepository
{
    const ROOT_ALIAS = 'country';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function findOrderedByLocale($locale)
    {
        return $this->createQueryBuilder('country')
            ->leftJoin('country.translations', 'translation')
            ->where('translation.locale = :locale')
            ->orderBy('translation.name', 'asc')
            ->setParameter('locale', $locale);
    }

    protected function getRootAlias()
    {
        return self::ROOT_ALIAS;
    }
}
