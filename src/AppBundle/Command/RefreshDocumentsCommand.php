<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Command;

use AppBundle\Entity\Document;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshDocumentsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('invox:document:refresh')
            ->setDescription('Refresh document totals and status')
            ->addOption('rows', 'r', InputOption::VALUE_NONE, 'Recalculate rows totals')
            ->addOption('company', 'c', InputOption::VALUE_NONE, 'Restore company data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        if ($input->getOption('rows')) {
            $this->refreshRows($em, $output);
        }

        if ($input->getOption('company')) {
            $this->refreshCompanies($em, $output);
        }
    }

    protected function refreshRows(EntityManager $em, OutputInterface $output)
    {
        $rows = $em->getRepository('AppBundle:DocumentRow')->findAll();

        $output->writeln(sprintf('Updating %d rows', count($rows)));

        foreach ($rows as $row) {
            $row->calculateTotals();

            $em->persist($row);
        }

        $em->flush();
    }

    protected function refreshCompanies(EntityManager $em, OutputInterface $output)
    {
        $documents = $em->getRepository('AppBundle:Document')->findAll();

        $output->writeln(sprintf('Updating %d documents', count($documents)));

        /** @var Document $document */
        foreach ($documents as $document) {
            $document->copyCompanyDetails();

            $em->persist($document);
        }

        $em->flush();
    }

}