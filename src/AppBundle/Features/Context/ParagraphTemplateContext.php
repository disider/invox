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

use AppBundle\Entity\ParagraphTemplate;
use Behat\Gherkin\Node\TableNode;

class ParagraphTemplateContext extends BaseMinkContext
{
    /**
     * @Given /^there is a paragraph template:$/
     * @Given /^there are paragraph templates:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['company'],
                $this->getValue($values, 'parent'),
                $values['title'],
                $this->getValue($values, 'description', $values['title'] . ' description')
            );

            $this->getParagraphTemplateRepository()->save($entity);
        }
    }

    /**
     * @Given /^there is a paragraph template subparagraph:$/
     * @Given /^there are paragraph template subparagraphs:$/
     */
    public function thereAreSubParagraphs(TableNode $table)
    {
        $this->buildParagraphs($table);
    }

    private function buildEntity($companyName, $parentTitle, $title, $description)
    {
        $company = $this->getCompanyRepository()->findOneByName($companyName);
        $parent = $this->getParagraphTemplateRepository()->findOneByTitle($parentTitle);

        $paragraph = new ParagraphTemplate();
        $paragraph->setTitle($title);
        $paragraph->setDescription($description);

        if ($company) {
            $paragraph->setCompany($company);
        }

        if ($parent) {
            $paragraph->setParent($parent);
        }

        return $paragraph;
    }

    private function buildParagraphs(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            /** @var ParagraphTemplate $paragraphTemplate */
            $paragraphTemplate = $this->getParagraphTemplateRepository()->findOneByName($values['parent']);

            $paragraph = $this->buildSubParagraph(
                $values['title'],
                $this->getValue($values, 'description', $values['title'])
            );

            $paragraphTemplate->addParagraph($paragraph);

            $this->getParagraphTemplateRepository()->save($paragraphTemplate);
        }
    }

    private function buildSubParagraph($title, $description)
    {
        $paragraph = new ParagraphTemplate();
        $paragraph->setTitle($title);
        $paragraph->setDescription($description);

        return $paragraph;
    }

}
