<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Helper;

use Doctrine\ORM\EntityManagerInterface;

class PlacesManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function savePlaces(array $places)
    {
        foreach ($places as $place) {
            $this->saveCountry($place);
            $this->saveProvince($place);
            $this->saveCity($place);
            $this->saveZipCode($place);
        }
    }

    private function saveCountry($place)
    {
        $country = $this->entityManager->getRepository('AppBundle:Country')
            ->findOneByCode($place['COUNTRY']);

        if (!is_object($country)) {
            $country = $place['COUNTRY'];
            $this->insert("INSERT INTO country (code) VALUES(\"$country\")");
        }
    }

    private function saveProvince($place)
    {
        $province = $this->entityManager->getRepository('AppBundle:Province')
            ->findOneByName($place['PROVINCE']);

        if (!is_object($province)) {
            $province = $place['PROVINCE'];
            $provinceCode = $place['PROVINCECODE'];
            $country = $this->entityManager->getRepository('AppBundle:Country')
                ->findOneByCode($place['COUNTRY'])
                ->getId();

            $this->insert("INSERT INTO province (country_id,name,code) VALUES(\"$country\",\"$province\",\"$provinceCode\")");
        }
    }

    private function saveCity($place)
    {
        $city = $this->entityManager->getRepository('AppBundle:City')
            ->findOneByName($place['CITY']);
        if (!is_object($city)) {
            $city = $place['CITY'];

            $province = $this->entityManager->getRepository('AppBundle:Province')
                ->findOneByName($place['PROVINCE'])
                ->getId();

            $this->insert("INSERT INTO city (province_id,name) VALUES(\"$province\", \"$city\")");
        }
    }

    private function saveZipCode($place)
    {
        $zipCode = $this->entityManager->getRepository('AppBundle:ZipCode')
            ->findOneByCode($place['ZIPCODE']);

        if (!is_object($zipCode) || $zipCode->getCity()->getName() != $place['CITY']) {

            $city = $this->entityManager->getRepository('AppBundle:City')
                ->findOneByName($place['CITY'])
                ->getId();

            $matches = [];
            preg_match_all('/\d\d\d\d\d/i', $place['ZIPCODE'], $matches);

            if (count($matches[0]) > 1) {
                $firstZipCode = intval($matches[0][0]);
                $lastZipCode = intval($matches[0][1]);

                for ($zipCode = $firstZipCode; $zipCode <= $lastZipCode; $zipCode++) {
                    $zipCode = str_pad($zipCode, 5, "0", STR_PAD_LEFT);
                    $this->insert("INSERT INTO zip_code (city_id,code) VALUES(\"$city\",\"$zipCode\")");
                }
            } else {
                $zipCode = $place['ZIPCODE'];
                $this->insert("INSERT INTO zip_code (city_id,code) VALUES(\"$city\",\"$zipCode\")");
            }
        }
    }

    private function insert($queryString)
    {
        $query = $this->entityManager->getConnection()->prepare($queryString);
        $query->execute();
    }
}