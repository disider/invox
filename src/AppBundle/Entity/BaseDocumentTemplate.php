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

class BaseDocumentTemplate
{
    /** @var int */
    protected $id;

    /**
     * @Assert\NotBlank(message="error.empty_name")
     * @var string
     */
    protected $name;

    /**
     * @Assert\NotBlank(message="error.empty_text_color")
     * @var string
     */
    protected $textColor;

    /**
     * @Assert\NotBlank(message="error.empty_table_header_background_color")
     * @var string
     */
    protected $tableHeaderBackgroundColor;

    /**
     * @Assert\NotBlank(message="error.empty_table_header_color")
     * @var string
     */
    protected $tableHeaderColor;

    /**
     * @Assert\NotBlank(message="error.empty_heading_color")
     * @var string
     */
    protected $headingColor;

    /**
     * @Assert\NotBlank(message="error.empty_font_family")
     * @var string
     */
    protected $fontFamily;

    /**
     * @Assert\NotBlank(message="error.empty_header")
     * @var string
     */
    protected $header;

    /**
     * @Assert\NotBlank(message="error.empty_content")
     * @var string
     */
    protected $content;

    /**
     * @Assert\NotBlank(message="error.empty_footer")
     * @var string
     */
    protected $footer;

    function __toString()
    {
        return $this->getName() ? $this->getName() : '';
    }

    public function getTextColor()
    {
        return $this->textColor;
    }

    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
    }

    public function getTableHeaderBackgroundColor()
    {
        return $this->tableHeaderBackgroundColor;
    }

    public function setTableHeaderBackgroundColor($tableHeaderBackgroundColor)
    {
        $this->tableHeaderBackgroundColor = $tableHeaderBackgroundColor;
    }

    public function getTableHeaderColor()
    {
        return $this->tableHeaderColor;
    }

    public function setTableHeaderColor($tableHeaderColor)
    {
        $this->tableHeaderColor = $tableHeaderColor;
    }

    public function getHeadingColor()
    {
        return $this->headingColor;
    }

    public function setHeadingColor($headingColor)
    {
        $this->headingColor = $headingColor;
    }

    public function getFontFamily()
    {
        return $this->fontFamily;
    }

    public function setFontFamily($fontFamily)
    {
        $this->fontFamily = $fontFamily;
    }

    public function getId()
    {
        return $this->id;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function setHeader($header)
    {
        $this->header = $header;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getFooter()
    {
        return $this->footer;
    }

    public function setFooter($footer)
    {
        $this->footer = $footer;
    }
}