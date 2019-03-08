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

use App\Entity\Document;
use App\Entity\DocumentRow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshDocumentsCommand extends Command
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
            ->setName('invox:document:refresh')
            ->setDescription('Refresh document totals and status')
            ->addOption('rows', 'r', InputOption::VALUE_NONE, 'Recalculate rows totals')
            ->addOption('company', 'c', InputOption::VALUE_NONE, 'Restore company data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('rows')) {
            $this->refreshRows($output);
        }

        if ($input->getOption('company')) {
            $this->refreshCompanies($output);
        }
    }

    protected function refreshRows(OutputInterface $output)
    {
        $rows = $this->entityManager->getRepository(DocumentRow::class)->findAll();

        $output->writeln(sprintf('Updating %d rows', count($rows)));

        foreach ($rows as $row) {
            $row->calculateTotals();

            $this->entityManager->persist($row);
        }

        $this->entityManager->flush();
    }

    protected function refreshCompanies(OutputInterface $output)
    {
        $documents = $this->entityManager->getRepository(Document::class)->findAll();

        $output->writeln(sprintf('Updating %d documents', count($documents)));

        /** @var Document $document */
        foreach ($documents as $document) {
            $document->copyCompanyDetails();

            $this->entityManager->persist($document);
        }

        $this->entityManager->flush();
    }

}