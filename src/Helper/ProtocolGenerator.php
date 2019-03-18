<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Helper;

use App\Entity\Company;
use App\Entity\Repository\ProtocolRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProtocolGenerator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate($class, Company $company, $year, $filters = [])
    {
        /** @var ProtocolRepository $repo */
        $repo = $this->entityManager->getRepository($class);

        $lastProtocolNumber = $repo->findLastProtocolNumber($company, $year, $filters);

        return $this->increment($lastProtocolNumber);
    }

    public function increment($value)
    {
        preg_match_all('/(\d+)/', $value, $matches, PREG_SET_ORDER);
        $lastMatch = end($matches);

        return substr($value, 0, strlen($value) - strlen($lastMatch[1])).($lastMatch[1] + 1);
    }
}
