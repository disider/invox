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

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class Tag
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Taggable
     */
    protected $taggable;

    /**
     * @var string
     * @JMS\Expose()
     */
    protected $name;

    public function __toString()
    {
        return $this->name;
    }

    public function getTaggable()
    {
        return $this->taggable;
    }

    public function setTaggable($taggable)
    {
        $this->taggable = $taggable;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

}
