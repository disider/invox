<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class Paragraph
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_title")
     *
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description = '';

    /**
     * @Assert\Valid
     *
     * @var ArrayCollection
     */
    private $children;

    /**
     * @var Paragraph
     */
    private $parent;

    /**
     * @var WorkingNote
     */
    private $workingNote;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    private function setChildren($childern)
    {
        $this->children = $childern;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(Paragraph $child)
    {
        $this->children->add($child);

        $child->setParent($this);
    }

    public function removeChild(Paragraph $child)
    {
        $this->children->removeElement($child);
    }

    public function setParent(Paragraph $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setWorkingNote(WorkingNote $workingNote)
    {
        $this->workingNote = $workingNote;
    }

    public function getWorkingNote()
    {
        return $this->workingNote;
    }
}
