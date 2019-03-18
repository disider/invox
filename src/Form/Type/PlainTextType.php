<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlainTextType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'widget' => 'field',
                'read_only' => true,
                'disabled' => true,
                'date_format' => null,
                'date_pattern' => null,
                'time_format' => null,
                'time_zone' => 'UTC',
                'attr' => [
                    'class' => 'plain_type',
                ],
                'compound' => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $value = $form->getViewData();
        // set string representation
        if (true === $value) {
            $value = 'true';
        } elseif (false === $value) {
            $value = 'false';
        } elseif (null === $value) {
            $value = 'null';
        } elseif (is_array($value)) {
            $value = implode(', ', $value);
        } elseif ($value instanceof \DateTime) {
            $dateFormat = is_int($options['date_format']) ? $options['date_format'] : \DateType::DEFAULT_FORMAT;
            $timeFormat = is_int($options['time_format']) ? $options['time_format'] : \DateType::DEFAULT_FORMAT;
            $calendar = \IntlDateFormatter::GREGORIAN;
            $pattern = is_string($options['date_pattern']) ? $options['date_pattern'] : null;
            $formatter = new \IntlDateFormatter(
                \Locale::getDefault(),
                $dateFormat,
                $timeFormat,
                $options['time_zone'],
                $calendar,
                $pattern
            );
            $formatter->setLenient(false);
            $value = $formatter->format($value);
        } elseif (is_object($value)) {
            if (method_exists($value, '__toString')) {
                $value = $value->__toString();
            } else {
                $value = get_class($value);
            }
        }
        $view->vars['value'] = (string)$value;
    }

    public function getBlockPrefix()
    {
        return 'plain_text';
    }

}
