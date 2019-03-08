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

use App\Entity\DocumentTemplatePerCompany;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RestoreDocumentTemplatesCommand extends Command
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
            ->setName('invox:document-template:restore')
            ->setDescription('Restore company document templates from base templates');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $templates = $this->entityManager->getRepository(DocumentTemplatePerCompany::class)->findAll();

        $output->writeln(sprintf('Updating %d templates', count($templates)));

        foreach ($templates as $template) {
            $template->copyDocumentTemplateDetails();
            $this->entityManager->persist($template);
        }

        $this->entityManager->flush();
    }

}