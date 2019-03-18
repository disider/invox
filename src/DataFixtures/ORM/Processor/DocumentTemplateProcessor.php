<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\DataFixtures\ORM\Processor;

use App\Entity\DocumentTemplate;
use App\Entity\DocumentTemplatePerCompany;
use Fidry\AliceDataFixtures\ProcessorInterface;

class DocumentTemplateProcessor implements ProcessorInterface
{

    /**
     * {@inheritdoc}
     */
    public function preProcess(string $id, $object): void
    {
        if ($object instanceof DocumentTemplate) {
            $object->setHeader($this->loadContents('header'));
            $object->setContent($this->loadContents('content'));
            $object->setFooter($this->loadContents('footer'));
        }

        if ($object instanceof DocumentTemplatePerCompany) {
            $object->copyDocumentTemplateDetails();
        }

    }

    /**
     * {@inheritdoc}
     */
    public function postProcess(string $id, $object): void
    {
    }

    private function loadContents($section)
    {
        return file_get_contents(sprintf('%s/../html/%s.html', __DIR__, $section));
    }
}