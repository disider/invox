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
use App\Model\DocumentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ImportDocumentDescriptionsCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('invox:document:import-descriptions')
            ->setDescription('Import document descriptions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $documentTypes = [
            3 => 'quote',
            4 => 'order',
            5 => 'invoice',
        ];

        foreach ($documentTypes as $key => $value) {
            $documents = $this->loadDocuments(sprintf('%s0-%ss', $key, $value), $output);
            $rows = $this->loadRows(sprintf('%s5-%s-rows', $key, $value), $output);

            $documents = $this->updateDescriptions($rows, $documents);

            $this->updateDocuments($documents, $output);
        }
    }

    protected function loadYaml($filename)
    {
        $path = __DIR__.'/../DataFixtures/ORM/prod/';

        $file = sprintf(
            '%s/'.$filename.'.yml',
            $path
        );

        return Yaml::parse(file_get_contents($file));
    }

    protected function updateDocuments($documents, OutputInterface $output)
    {
        $repository = $this->em->getRepository(Document::class);

        foreach ($documents as $record) {
            $ref = $record['ref'];
            $type = $record['type'];
            $year = $record['year'];

            $direction = isset($record['direction']) ? $record['direction'] : 'none';
            $customerName = $record['customerName'];

            /** @var Document $document */
            $document = $repository->findOneBy(
                [
                    'ref' => $ref,
                    'year' => $year,
                    'direction' => $direction,
                    'type' => $type,
                    'customerName' => $customerName,
                ]
            );

            if (!$document && ($type == DocumentType::CREDIT_NOTE)) {
                $output->writeln(
                    sprintf('Searching for %s for %s/%s (%s), %s', $type, $ref, $year, $direction, $customerName)
                );

                /** @var Document $document */
                $document = $repository->findOneBy(
                    [
                        'ref' => $ref,
                        'year' => $year,
                        'direction' => 'none',
                        'type' => DocumentType::CREDIT_NOTE,
                        'customerName' => $customerName,
                    ]
                );
            }

            if (!$document) {
                $output->writeln('Document '.$ref.' not found ('.$type.')');
                continue;
            }

            $rows = $document->getRows();
            $rowsCount = count($rows);
            $recordsCount = count($record['descriptions']);

            if ($rowsCount != $recordsCount) {
                $output->writeln(
                    sprintf(
                        'Rows count for '.$ref.' does not match (%d instead of %d)',
                        $ref,
                        $rowsCount,
                        $recordsCount
                    )
                );
                continue;
            }

            /** @var DocumentRow $row */
            foreach ($rows as $i => $row) {
                $row->setDescription($record['descriptions'][$i]);
                $this->em->persist($row);
            }
        }

        $this->em->flush();
    }

    protected function updateDescriptions($rows, $documents)
    {
        foreach ($rows as $row) {
            $key = substr($row['document'], 1);
            $document = $documents[$key];

            if (!isset($document['descriptions'])) {
                $document['descriptions'] = [];
            }

            $document['descriptions'][] = $row['description'];

            $documents[$key] = $document;
        }

        return $documents;
    }

    protected function loadRows($filename, OutputInterface $output)
    {
        $output->writeln('Loading '.$filename);
        $yaml = $this->loadYaml($filename);
        $class = key($yaml);

        return $yaml[$class];
    }

    protected function loadDocuments($filename, OutputInterface $output)
    {
        $output->writeln('Loading '.$filename);
        $yaml = $this->loadYaml($filename);
        $class = key($yaml);

        return $yaml[$class];
    }
}