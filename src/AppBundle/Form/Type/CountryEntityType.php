<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Country;
use AppBundle\Entity\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryEntityType extends AbstractType
{
    /**
     * @var string
     */
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $locale = $this->locale;

        $resolver->setDefaults([
            'label' => 'fields.country',
            'class' => Country::class,
            'query_builder' => function(CountryRepository $repository) use($locale) {
                return $repository->findOrderedByLocale($locale);
            },
            'attr' => [
                'class' => 'input-sm',
            ]
        ]);
    }
    
    public function getBlockPrefix()
    {
        return 'countryEntity';
    }
}
