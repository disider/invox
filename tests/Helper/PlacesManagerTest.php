<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\App\Helper;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Province;
use App\Entity\ZipCode;
use App\Helper\PlacesManager;
use Tests\App\RepositoryTestCase;
use Doctrine\ORM\EntityManager;

class PlacesManagerTest extends RepositoryTestCase
{
    /** @var EntityManager */
    private $em;

    private $countryRepo;
    private $provinceRepo;
    private $cityRepo;
    private $zipCodeRepo;

    /** @var PlacesManager */
    private $placesManger;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->em = $this->getService('doctrine.orm.entity_manager');
        $this->countryRepo = $this->em->getRepository(Country::class);
        $this->provinceRepo = $this->em->getRepository(Province::class);
        $this->cityRepo = $this->em->getRepository(City::class);
        $this->zipCodeRepo = $this->em->getRepository(ZipCode::class);
        $this->placesManger = new PlacesManager($this->em);
    }

    /**
     * @test
     */
    public function testSaveDifferentPlaces()
    {
        $data = [
            [
                'CITY' => 'Palestrina',
                'ZIPCODE' => '00036',
                'PROVINCECODE' => 'RM',
                'PROVINCE' => 'Roma',
                'COUNTRY' => 'IT',
            ],
            [
                'CITY' => 'Sant\'Anna',
                'ZIPCODE' => '09016',
                'PROVINCECODE' => 'CI',
                'PROVINCE' => 'L\'Aquila',
                'COUNTRY' => 'IT',
            ],
        ];

        $this->placesManger->savePlaces($data);

        $this->verifyPlaces(1, 2, 2, 2);
    }

    /**
     * @test
     */
    public function testSaveSamePlace()
    {
        $data = [
            [
                'CITY' => 'Palestrina',
                'ZIPCODE' => '00036',
                'PROVINCECODE' => 'RM',
                'PROVINCE' => 'Roma',
                'COUNTRY' => 'IT',
            ],
            [
                'CITY' => 'Palestrina',
                'ZIPCODE' => '00036',
                'PROVINCECODE' => 'RM',
                'PROVINCE' => 'Roma',
                'COUNTRY' => 'IT',
            ],
        ];

        $this->placesManger->savePlaces($data);

        $this->verifyPlaces(1, 1, 1, 1);
    }

    /**
     * @test
     */
    public function testDuplicateSameZipCodeForTwoCities()
    {
        $data = [
            [
                'CITY' => 'Palestrina',
                'ZIPCODE' => '00036',
                'PROVINCECODE' => 'RM',
                'PROVINCE' => 'Roma',
                'COUNTRY' => 'IT',
            ],
            [
                'CITY' => 'Ostia',
                'ZIPCODE' => '00036',
                'PROVINCECODE' => 'RM',
                'PROVINCE' => 'Roma',
                'COUNTRY' => 'IT',
            ],
        ];

        $this->placesManger->savePlaces($data);

        $this->verifyPlaces(1, 1, 2, 2);
    }

    /**
     * @test
     */
    public function testTwoZipCodeForOneCity()
    {
        $data = [
            [
                'CITY' => 'Palestrina d\'aquila',
                'ZIPCODE' => 'da 00036 a  00037',
                'PROVINCECODE' => 'RM',
                'PROVINCE' => 'Roma',
                'COUNTRY' => 'IT',
            ],
        ];

        $this->placesManger->savePlaces($data);

        $this->verifyPlaces(1, 1, 1, 2);
    }

    /**
     * @param $numCountries
     * @param $numProvinces
     * @param $numCities
     * @param $numZipCodes
     */
    private function verifyPlaces($numCountries, $numProvinces, $numCities, $numZipCodes)
    {
        $this->assertCount($numCountries, $this->countryRepo->findAll());
        $this->assertCount($numProvinces, $this->provinceRepo->findAll());
        $this->assertCount($numCities, $this->cityRepo->findAll());
        $this->assertCount($numZipCodes, $this->zipCodeRepo->findAll());
    }
}
