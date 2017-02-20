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

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Dumper;

class ImportTranslationsCommand extends ContainerAwareCommand
{
    protected $locales = [
        'en',
        'it',
    ];

    protected $domains = [
        'messages',
        'validators',
        'emails',
    ];

    /** @var Client */
    private $client;

    private $key;

    private $path;

    protected function configure()
    {
        $this
            ->setName('invox:translations:import')
            ->setDescription('Import translations file from Loco');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client = new Client();

        $container = $this->getContainer();

        $path = $container->getParameter('kernel.root_dir') . '/Resources/translations';
        $key = $container->getParameter('loco_api_key');
        $filesystem = $container->get('filesystem');

        foreach ($this->locales as $locale) {
            foreach ($this->domains as $domain) {
                $this->importTranslation($locale, $domain, $path, $key, $filesystem, $output);
            }
        }
    }

    protected function importTranslation($locale, $domain, $path, $key, $filesystem, OutputInterface $output)
    {
        $output->writeln(sprintf('Importing: %s (%s)', $domain, $locale));

        $url = "https://localise.biz/api/export/locale/{$locale}.yml?format=symfony&filter={$domain}&status=translated&key={$key}";
        $content = $this->client->request('GET', $url)->getBody()->getContents();
        $content = str_replace("  ", "    ", $content);

        $yaml = substr($content, 0, -4);

        $filename = sprintf('%s/%s.%s.yml', $path, $domain, $locale);

//        $this->backup($locale, $domain, $path, $filesystem, $filename);
        $this->sort($yaml, $filename, $filesystem);
    }

    protected function backup($locale, $domain, $path, $filesystem, $filename)
    {
        $timestamp = new \DateTime();
        $backupPath = sprintf('%s/%s', $path, $timestamp->format('Ymdhi'));

        @mkdir($backupPath);

        $backupFilename = sprintf('%s/%s.%s.yml', $backupPath, $domain, $locale);

        $filesystem->rename($filename, $backupFilename);
    }

    private function sort($yaml, $filename, $filesystem)
    {
        $tempFilename = $filename . '.tmp';
        $filesystem->dumpFile($tempFilename, $yaml, null);

        shell_exec(sprintf('sort_yaml < %s > %s', $tempFilename, $filename));
        @unlink($tempFilename);
    }
}