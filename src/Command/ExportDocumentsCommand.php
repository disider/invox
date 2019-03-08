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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportDocumentsCommand extends Command
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
            ->setName('invox:document:export')
            ->setDescription('Refresh document totals and status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $docs = $this->entityManager->getRepository(Document::class)->findBy(
//            ['status' => 'unpaid', 'direction' => 'outgoing', 'type' => 'invoice']
//        );
//
//        $file = fopen('documents-export.csv', 'w');
//
//        $output->writeln(sprintf('Exporting %d documents', count($docs)));
//
//        /** @var Document $doc */
//        foreach ($docs as $doc) {
//            $row = [
//                $doc->getType(),
//                $doc->formatRef(),
//                $doc->getCustomerName(),
//                $this->formatDate($doc->getValidUntil()),
//                $doc->getGrossTotal(),
//            ];
//
//            $output->writeln(sprintf('Found %d notes', $doc->getPettyCashNotes()->count()));
//
//            /** @var InvoicePerNote $invoicePerNote */
//            foreach ($doc->getPettyCashNotes() as $invoicePerNote) {
//                $note = $invoicePerNote->getNote();
//                $row = array_merge(
//                    $row,
//                    [
//                        $note->getRef(),
//                        $this->formatDate($note->getRecordedAt()),
//                        $invoicePerNote->getAmount(),
//                    ]
//                );
//            }
//
//            fputcsv($file, $row);
//        }
//
//        fclose($file);
    }

    private function formatDate(\DateTime $date = null)
    {
        if ($date) {
            return $date->format('d/m/Y');
        }

        return '';
    }

}