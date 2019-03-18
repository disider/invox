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

use App\Form\Transformer\DayOfWeekTransformer;
use App\Helper\WeekdayHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeekdaysType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new DayOfWeekTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'label' => 'fields.weekdays',
                'choices' => $this->formatWeekdays(),
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ]
        );
    }

    public function formatWeekdays($weekdays = '1111111')
    {
        if (strlen($weekdays) < 7) {
            return [];
        }

        $days = [];
        $names = WeekdayHelper::daysValues();

        $weekdays = str_split($weekdays);

        for ($i = 0; $i < 7; ++$i) {
            if ($weekdays[$i] != '0') {
                $days['days.'.$names[$i]] = $i;
            }
        }

        return $days;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'weekdays';
    }
}
