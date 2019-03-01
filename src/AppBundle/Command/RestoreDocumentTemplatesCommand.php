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

use LegacyBundle\Entity\Customer;
use LegacyBundle\Model\ReferenceTable;
use LegacyBundle\Util\Util;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RestoreDocumentTemplatesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('invox:document-template:restore')
            ->setDescription('Restore company document templates from base templates');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $templates = $em->getRepository('AppBundle:DocumentTemplatePerCompany')->findAll();

        $output->writeln(sprintf('Updating %d templates', count($templates)));

        foreach ($templates as $template) {
            $template->copyDocumentTemplateDetails();
            $em->persist($template);
        }

        $em->flush();
    }

}