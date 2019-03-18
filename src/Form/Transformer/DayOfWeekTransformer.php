<?php

namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class DayOfWeekTransformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if (empty($value)) {
            return [];
        }

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            if ($value[$i] != '0') {
                $days[] = $i;
            }
        }

        return $days;
    }

    public function reverseTransform($values)
    {
        $weekdays = '0000000';

        foreach ($values as $value) {
            $weekdays[$value] = '1';
        }

        return $weekdays;
    }
}
