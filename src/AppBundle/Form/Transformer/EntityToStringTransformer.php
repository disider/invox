<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Transformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToStringTransformer implements DataTransformerInterface
{
    /** @var EntityManager */
    private $manager;

    /** @var string */
    private $class;

    /** @var */
    private $field;

    public function __construct(EntityManager $manager, $class, $field)
    {
        $this->manager = $manager;
        $this->class = $class;
        $this->field = $field;
    }

    /**
     * Transforms an object to a string.
     */
    public function transform($entity)
    {
        if (null === $entity) {
            return '';
        }

        $method = 'get' . ucfirst($this->field);

        return (string)$entity->$method();
    }

    /**
     * Transforms a string to an entity
     */
    public function reverseTransform($text)
    {
        // no text? It's optional, so that's ok
        if (empty(trim($text))) {
            return null;
        }

        $entity = $this->manager
            ->getRepository($this->class)
            // query for the issue with this id
            ->findOneBy([$this->field => $text]);

        if (null === $entity) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An object with %s "%s" does not exist!',
                $this->field,
                $text
            ));
        }

        return $entity;
    }
}