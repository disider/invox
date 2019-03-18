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

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;

class Page
{
    use ORMBehaviors\Translatable\Translatable;

    /** @var int */
    private $id;

    /**
     * @Assert\Valid
     */
    protected $translations;

    public static function create($title, $url, $content)
    {
        $entity = new self();
        $entity->translate('en')->setTitle($title);
        $entity->translate('en')->setUrl($url);
        $entity->translate('en')->setContent($content);

        return $entity;
    }

    public function __toString()
    {
        return $this->proxyCurrentLocaleTranslation('getTitle');
    }

    public function getId()
    {
        return $this->id;
    }

    public function __call($method, $arguments)
    {
        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
    }
}
