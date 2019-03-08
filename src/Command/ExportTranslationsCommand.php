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

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportTranslationsCommand extends Command
{
    private $key;

    private $path;

    protected $domains = [
        'messages',
        'validators',
        'emails',
    ];

    protected function configure()
    {
        $this
            ->setName('invox:translations:export')
            ->setDescription('Export translations file to Loco')
            ->addOption('locale', 'l', InputOption::VALUE_OPTIONAL, 'The locale to export')
            ->addOption('domain', 'd', InputOption::VALUE_OPTIONAL, 'The domain to export')
            ->addOption('all', 'a', InputOption::VALUE_OPTIONAL, 'Export all');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $this->path = $container->get('kernel')->getProjectDir().'/Resources/translations';
        $this->key = $container->getParameter('loco_api_key');

        if ($input->hasOption('all')) {
            $locales = $container->getParameter('available_locales');

            foreach ($locales as $locale) {
                foreach ($this->domains as $domain) {
                    $this->exportTranslation($locale, $domain, $output);
                }
            }
        } else {
            $locale = $input->getOption('locale');
            if (!$locale) {
                $locale = $container->getParameter('locale');
            }

            $domain = $input->getOption('domain');

            if (!$domain) {
                foreach ($this->domains as $domain) {
                    $this->exportTranslation($locale, $domain, $output);
                }
            } else {
                $this->exportTranslation($locale, $domain, $output);
            }
        }
    }

    protected function exportTranslation($locale, $domain, OutputInterface $output)
    {
        $output->writeln(sprintf('Exporting: %s (%s)', $domain, $locale));

        $url = "https://localise.biz/api/import/yml?locale={$locale}&tag={$domain}";

        $filename = sprintf('%s/%s.%s.yml', $this->path, $domain, $locale);

        $client = new Client();
        $client->request(
            'POST',
            $url,
            [
                'body' => file_get_contents($filename),
                'headers' => [
                    'Authorization' => 'Loco '.$this->key,
                ],
            ]
        );
    }
}