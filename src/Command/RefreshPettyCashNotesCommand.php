<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Command;

use App\Entity\PettyCashNote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshPettyCashNotesCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('invox:petty-cash-note:refresh')
            ->setDescription('Refresh petty cash note reference numbers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pettyCashNotes = $this->entityManager->getRepository(PettyCashNote::class)->findAllQuery()
            ->addOrderBy('note.recordedAt', 'ASC')
            ->addOrderBy('note.id', 'DESC')
            ->getQuery()->execute();

        $output->writeln(sprintf('Updating %d pettyCashNotes', count($pettyCashNotes)));

        $count = 0;
        $year = 0;

        /** @var PettyCashNote $pettyCashNote */
        foreach ($pettyCashNotes as $pettyCashNote) {
            if ($year != $pettyCashNote->getRecordedAt()->format('Y')) {
                $year = $pettyCashNote->getRecordedAt()->format('Y');

                $output->writeln(sprintf('Year %d', $year));
                $count = 0;
            }

            $pettyCashNote->setRef(++$count);
            $this->entityManager->persist($pettyCashNote);
        }

        $this->entityManager->flush();
    }

}