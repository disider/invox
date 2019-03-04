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

use AppBundle\Helper\CsvToArray;
use AppBundle\Helper\PlacesManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPlacesCommand extends ContainerAwareCommand
{
    private $placesManager;
    private $csvToArray;

    public function __construct(PlacesManager $placesManager, CsvToArray $csvToArray)
    {
        parent::__construct();

        $this->placesManager = $placesManager;
        $this->csvToArray = $csvToArray;
    }

    protected function configure()
    {
        $this
            ->setName('invox:places:import')
            ->addArgument('file', InputArgument::REQUIRED, 'CSV file')
            ->setDescription('Import places from CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getData($input);

        $this->placesManager->savePlaces($data);
    }

    private function getData(InputInterface $input)
    {
        $fileName = $input->getArgument('file');

        return $this->csvToArray->convert($fileName, ';');
    }
}