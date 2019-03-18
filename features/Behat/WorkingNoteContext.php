<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Features\App;

use App\Entity\Paragraph;
use App\Entity\WorkingNote;
use Behat\Gherkin\Node\TableNode;

class WorkingNoteContext extends AbstractMinkContext
{
    /**
     * @Given /^there is a working note:$/
     * @Given /^there are working notes:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['company'],
                $values['title'],
                $this->getValue($values, 'code', $values['title'])
            );

            $this->getWorkingNoteRepository()->save($entity);
        }
    }

    /**
     * @Given /^there is a working note paragraph:$/
     * @Given /^there are working note paragraphs:$/
     */
    public function thereAreParagraphs(TableNode $table)
    {
        $this->buildParagraphs($table);
    }

    private function buildEntity($companyName, $title, $code)
    {
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        return WorkingNote::create($company, $title, $code);
    }

    private function buildParagraphs(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            /** @var WorkingNote $workingNote */
            $workingNote = $this->getWorkingNoteRepository()->findOneByCode($values['workingNote']);

            $paragraph = $this->buildParagraph(
                $values['title'],
                $this->getValue($values, 'description', $values['title'])
            );

            $workingNote->addParagraph($paragraph);

            $this->getWorkingNoteRepository()->save($workingNote);
        }
    }

    private function buildParagraph($title, $description)
    {
        $paragraph = new Paragraph();
        $paragraph->setTitle($title);
        $paragraph->setDescription($description);

        return $paragraph;
    }

}
