<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use App\Entity\Page;
use Behat\Gherkin\Node\TableNode;

class PageContext extends BaseMinkContext
{
    /**
     * @Given /^there is a page:$/
     * @Given /^there are pages:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['title'],
                $this->getValue($values, 'url', strtolower($values['title'])),
                $this->getValue($values, 'content', 'Page content')
            );

            $this->getPageRepository()->save($entity);
        }
    }

    private function buildEntity($title, $url, $content)
    {
        $page = Page::create($title, $url, $content);
        $page->mergeNewTranslations();

        return $page;
    }
}
