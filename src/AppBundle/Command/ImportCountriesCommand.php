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

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Dumper;

class ImportCountriesCommand extends ContainerAwareCommand
{
    protected $locales = [
        'en',
        'it',
    ];

    /** @var Client */
    private $client;

    protected function configure()
    {
        $this
            ->setName('invox:countries:import')
            ->setDescription('Import countries data from GeoNames');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client = new Client();

        $container = $this->getContainer();

        $path = sprintf('%s/../src/AppBundle/DataFixtures/ORM/%s',
            $container->getParameter('kernel.root_dir'),
            $container->getParameter('kernel.environment'))
        ;

        $this->importCountryCodes($path, $output);

        foreach ($this->locales as $locale) {
            $this->importCountryNames($locale, $path, $output);
        }
    }

    protected function importCountryCodes($path, OutputInterface $output)
    {
        $output->writeln(sprintf('Importing country codes'));

        $url = "http://api.geonames.org/countryInfoJSON?username=beoboo";
        $content = $this->client->request('GET', $url)->getBody()->getContents();

        $records = json_decode($content, true);

        $codes = [];

        foreach($records['geonames'] as $record) {
            $countryCode = $record['countryCode'];
            $codes['country_' . strtolower($countryCode)] = [
                'code' => $countryCode
            ];
        }
        $this->dump($path . '/00-countries.yml', 'AppBundle\Entity\Country', $codes);

        $output->writeln(sprintf('Imported %d country codes', count($codes)));
    }

    protected function importCountryNames($locale, $path, OutputInterface $output)
    {
        $output->writeln(sprintf('Importing countries for "%s" locale', $locale));

        $url = "http://api.geonames.org/countryInfoJSON?username=beoboo&lang={$locale}";
        $content = $this->client->request('GET', $url)->getBody()->getContents();

        $records = json_decode($content, true);

        $translations = [];

        foreach($records['geonames'] as $record) {
            $countryName = $record['countryName'];
            $code = strtolower($record['countryCode']);
            $translations[sprintf('country_%s_%s', $code, $locale)] = [
                'translatable' => '@country_' . $code,
                'name' => $countryName,
                'locale' => $locale
            ];
        }
        $this->dump(sprintf('%s/05-countries-%s.yml', $path, $locale), 'AppBundle\Entity\CountryTranslation', $translations);
    }

    protected function dump($filename, $class, $codes)
    {
        $dumper = new Dumper();
        $yaml = $dumper->dump([
            $class => $codes
        ], 4, 0);

        file_put_contents($filename, $yaml);
    }
}