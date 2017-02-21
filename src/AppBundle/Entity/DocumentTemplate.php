<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class DocumentTemplate extends BaseDocumentTemplate
{
    /** @var ArrayCollection */
    private $companies;

    public static function create($name, $textColor, $headingColor, $tableHeaderColor, $tableHeaderBackgroundColor, $fontFamily, $header, $content, $footer)
    {
        $entity = new self();
        $entity->setName($name);
        $entity->setTextColor($textColor);
        $entity->setTableHeaderBackgroundColor($tableHeaderBackgroundColor);
        $entity->setTableHeaderColor($tableHeaderColor);
        $entity->setHeadingColor($headingColor);
        $entity->setFontFamily($fontFamily);
        $entity->setHeader($header);
        $entity->setContent($content);
        $entity->setFooter($footer);

        return $entity;
    }

    public function __construct()
    {
        $this->companies = new ArrayCollection();
    }

    public function getCompanies()
    {
        return $this->companies;
    }

    public function hasCompanies()
    {
        return count($this->companies) > 0;
    }

    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }
}