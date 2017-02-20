<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Features\Context;

use AppBundle\Entity\DocumentTemplate;
use AppBundle\Entity\DocumentTemplatePerCompany;
use Behat\Gherkin\Node\TableNode;

class DocumentTemplateContext extends BaseMinkContext
{
    /**
     * @Given /^there is a document template:$/
     * @Given /^there are document templates:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = DocumentTemplate::create(
                $values['name'],
                $this->getValue($values, 'textColor', '#000000'),
                $this->getValue($values, 'headingColor', '#ffffff'),
                $this->getValue($values, 'tableHeaderColor', '#999999'),
                $this->getValue($values, 'tableHeaderBackgroundColor', '#efefef'),
                $this->getValue($values, 'fontFamily', '"Helvetica Neue", Helvetica, Arial, sans-serif'),
                $this->getContent($values, 'header', '/html/header.html'),
                $this->getContent($values, 'content', '/html/content.html'),
                $this->getContent($values, 'footer', '/html/footer.html')
            );

            $this->getDocumentTemplateRepository()->save($entity);
        }
    }

    /**
     * @Given /^there is a company document template:$/
     * @Given /^there are company document templates:$/
     */
    public function thereAreCompanyDocumentTemplates(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $template = $this->getDocumentTemplateRepository()->findOneByName($values['template']);
            $company = $this->getCompanyRepository()->findOneByName($values['company']);

            $entity = DocumentTemplatePerCompany::create($template, $company);

            if (isset($values['name'])) {
                $entity->setName($values['name']);
            }
            if (isset($values['header'])) {
                $entity->setHeader($values['header']);
            }
            if (isset($values['content'])) {
                $entity->setContent($values['content']);
            }
            if (isset($values['footer'])) {
                $entity->setFooter($values['footer']);
            }

            $this->getDocumentTemplatePerCompanyRepository()->save($entity);
        }
    }

    private function getContent($values, $key, $filename)
    {
        $filename = isset($values[$key]) ? $values[$key] : $filename;

        $path = __DIR__ . $filename;

        if(is_file($path)) {
            return file_get_contents($path);
        }

        return $filename;
    }
}
