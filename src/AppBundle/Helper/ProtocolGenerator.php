<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Helper;

use AppBundle\Entity\Company;
use AppBundle\Entity\Repository\ProtocolRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProtocolGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate($class, Company $company, $year, $filters = [])
    {
        /** @var ProtocolRepository $repo */
        $repo = $this->entityManager->getRepository($class);

        return $repo->findLastProtocolNumber($company, $year, $filters) + 1;
    }
}
