<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

class PageTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @Assert\NotBlank(message="error.empty_title")
     * @var string
     */
    private $title;

    /**
     * @Assert\NotBlank(message="error.empty_content")
     * @var string
     */
    private $content;

    /**
     * @Assert\NotBlank(message="error.empty_url")
     * @var string
     */
    private $url;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
}
