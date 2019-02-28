<?php

namespace AppBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if (!$value) {
            return [];
        }

        return implode(',', $value->toArray());
    }

    public function reverseTransform($value)
    {
        return $value;
    }
}
