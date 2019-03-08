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

use AppBundle\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshAccountsCommand extends Command
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
            ->setName('invox:account:refresh')
            ->setDescription('Refresh account totals');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accounts = $this->entityManager->getRepository(Account::class)->findAll();

        $output->writeln(sprintf('Updating %d accounts', count($accounts)));

        /** @var Account $account */
        foreach ($accounts as $account) {
            $account->calculateCurrentAmount();

            $output->writeln(sprintf('%s: %0.2f', $account, $account->getCurrentAmount()));
            $this->entityManager->persist($account);
        }

        $this->entityManager->flush();
    }

}