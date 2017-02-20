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

use AppBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshAccountsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('invox:account:refresh')
            ->setDescription('Refresh account totals');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $accounts = $em->getRepository('AppBundle:Account')->findAll();

        $output->writeln(sprintf('Updating %d accounts', count($accounts)));

        /** @var Account $account */
        foreach ($accounts as $account) {
            $account->calculateCurrentAmount();

            $output->writeln(sprintf('%s: %0.2f', $account, $account->getCurrentAmount()));
            $em->persist($account);
        }

        $em->flush();
    }

}