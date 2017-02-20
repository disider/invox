<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Command;

use AppBundle\Entity\Document;
use AppBundle\Entity\InvoicePerNote;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportDocumentsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('invox:document:export')
            ->setDescription('Refresh document totals and status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $docs = $em->getRepository('AppBundle:Document')->findBy(['status' => 'unpaid', 'direction' => 'outgoing', 'type' => 'invoice']);

        $file = fopen('documents-export.csv', 'w');

        $output->writeln(sprintf('Exporting %d documents', count($docs)));

        /** @var Document $doc */
        foreach ($docs as $doc) {
            $row = [
                $doc->getType(),
                $doc->formatRef(),
                $doc->getCustomerName(),
                $this->formatDate($doc->getValidUntil()),
                $doc->getGrossTotal()
            ];

            $output->writeln(sprintf('Found %d notes', $doc->getPettyCashNotes()->count()));

            /** @var InvoicePerNote $invoicePerNote */
            foreach ($doc->getPettyCashNotes() as $invoicePerNote) {
                $note = $invoicePerNote->getNote();
                $row = array_merge($row, [
                    $note->getRef(),
                    $this->formatDate($note->getRecordedAt()),
                    $invoicePerNote->getAmount()
                ]);
            }

            fputcsv($file, $row);
        }

        fclose($file);
    }

    private function formatDate(\DateTime $date = null)
    {
        if ($date) {
            return $date->format('d/m/Y');
        }

        return '';
    }

}