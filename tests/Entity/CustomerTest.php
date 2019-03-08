<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\App\Entity;

use App\Entity\Country;
use App\Entity\Customer;
use App\Entity\User;
use App\Entity\Company;
use Tests\App\EntityTest;

class CustomerTest extends EntityTest
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $name = 'Customer';
        $email = 'customer@example.com';
        $address = 'Abbey Road, 1234, London';
        $vatNumber = '01234567890';

        $owner = User::create('user@example.com', '', '');
        $country = Country::create('Italy');
        $company = Company::create($country, $owner, $name, $address, $vatNumber);
        $customer = Customer::create($company, $name, $email, $vatNumber, '', $country, 'Rome', 'RM', '00100', $address, '');

        $this->assertNull($customer->getId());
        $this->assertThat($customer->getName(), $this->equalTo($name));
        $this->assertThat($customer->getEmail(), $this->equalTo($email));
        $this->assertThat($customer->getAddress(), $this->equalTo($address));
        $this->assertThat($customer->getVatNumber(), $this->equalTo($vatNumber));
    }

    /**
     * @test
     */
    public function testToString()
    {
        $name = 'Customer';

        $owner = User::create('user@example.com', '', '');
        $country = Country::create('Italy');
        $company = Company::create($country, $owner, $name, '', '');
        $customer = Customer::create($company, $name, 'customer@example.com', '', '', $country, '', '', '', '', '');

        $this->assertThat((string) $customer, $this->equalTo($name));
    }
}
