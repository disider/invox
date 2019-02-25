<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Company;
use AppBundle\Entity\Country;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\Recurrence;
use AppBundle\Entity\User;
use DateInterval;
use Tests\AppBundle\EntityTest;

class RecurrenceTest extends EntityTest
{
    /**
     * @test
     */
    public function testCreate()
    {
        $country = $this->givenCountry();
        $company = $this->givenCompany($country);
        $customer = $this->givenCustomer($company, $country);

        $content = 'Content';
        $direction = Recurrence::OUTGOING;
        $startAt = new \DateTime();
        $repeats = Recurrence::EVERYDAY;
        $repeatEvery = 1;

        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWith($content, $direction, $startAt, $repeats, $repeatEvery, $customer, $company);

        $this->assertNull($recurrence->getId());
        $this->assertEquals($recurrence->getContent(), $content);
        $this->assertEquals($recurrence->getDirection(), $direction);
        $this->assertEquals($recurrence->getStartAt(), $startAt);
        $this->assertEquals($recurrence->getRepeats(), $repeats);
        $this->assertEquals($recurrence->getRepeatEvery(), $repeatEvery);
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrence();

        $recurrence->calculateNextDueDate();

        $this->assertEquals($recurrence->getStartAt(), $recurrence->getNextDueDate());
    }


    /**
     * @test
     */
    public function testRecurrenceIsFinished_GivenOccurrences()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERYDAY, 1, 2);
        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertNull($recurrence->getNextDueDate());
    }

    /**
     * @test
     */
    public function testRecurrenceIsFinished_GivenEndAt()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERYDAY, 2, 2);
        $recurrence->setEndAt(new \DateTime('2017-02-28'));
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertNull($recurrence->getNextDueDate());
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryDayWithOneDocument()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERYDAY);
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertEquals($this->calculateDate($recurrence->getStartAt(), '+1 day'), $recurrence->getNextDueDate());
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryDayWithDocuments()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERYDAY, 2);
        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertEquals($this->calculateDate($recurrence->getStartAt(), '+4 day'), $recurrence->getNextDueDate());
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryWeekWithOneDocument()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERY_WEEK);
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertEquals($this->calculateDate($recurrence->getStartAt(), '+1 week'), $recurrence->getNextDueDate());
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryWeekWithWeekdays()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERY_WEEK);
        $recurrence->setRepeatOn('0100110');

        $recurrence->calculateNextDueDate();

        $expectedDate = $this->calculateDate($recurrence->getStartAt(), 'next tuesday');

        $this->assertEquals($expectedDate, $recurrence->getNextDueDate());
        $this->assertEquals('Tuesday', $recurrence->getNextDueDate()->format('l'));

        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());
        $recurrence->calculateNextDueDate();

        $expectedDate = $this->calculateDate($recurrence->getStartAt(), 'next saturday');

        $this->assertEquals($expectedDate, $recurrence->getNextDueDate());
        $this->assertEquals('Saturday', $recurrence->getNextDueDate()->format('l'));
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryWeekWithMoreDocuments()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERY_WEEK, 2);
        $recurrence->setRepeatOn('0100110');
        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $expectedDate = $this->calculateDate($recurrence->getStartAt(), '+2 tuesday');

        $this->assertEquals($expectedDate, $recurrence->getNextDueDate());
        $this->assertEquals('Tuesday', $recurrence->getNextDueDate()->format('l'));

        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $expectedDate = $this->calculateDate($recurrence->getStartAt(), '+2 saturday');

        $this->assertEquals($expectedDate, $recurrence->getNextDueDate());
        $this->assertEquals('Saturday', $recurrence->getNextDueDate()->format('l'));
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryWeekWithDocuments()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERY_WEEK, 2);
        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertEquals($this->calculateDate($recurrence->getStartAt(), '+4 week'), $recurrence->getNextDueDate());
    }


    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryMonthWithOneDocument()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERY_MONTH);
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertEquals($this->calculateDate($recurrence->getStartAt(), '+1 month'), $recurrence->getNextDueDate());
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryMonthWithDocuments()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERY_MONTH, 2);
        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertEquals($this->calculateDate($recurrence->getStartAt(), '+4 month'), $recurrence->getNextDueDate());
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryYearWithOneDocument()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERY_YEAR);
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertEquals($this->calculateDate($recurrence->getStartAt(), '+1 year'), $recurrence->getNextDueDate());
    }

    /**
     * @test
     */
    public function testCalculateNextDueDate_GivenEveryYearWithDocuments()
    {
        /** @var Recurrence $recurrence */
        $recurrence = $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERY_YEAR, 2);
        $recurrence->addLinkedDocument(new Document());
        $recurrence->addLinkedDocument(new Document());

        $recurrence->calculateNextDueDate();

        $this->assertEquals($this->calculateDate($recurrence->getStartAt(), '+4 year'), $recurrence->getNextDueDate());
    }

    /**
     * @param $country
     * @return Company
     */
    private function givenCompany($country)
    {
        $owner = User::create('user@example.com', '', '');
        $company = Company::create($country, $owner, 'Customer', 'Abbey Road, 1234, London', '01234567890');
        return $company;
    }

    /**
     * @return Country
     */
    private function givenCountry()
    {
        $country = Country::create('Italy');
        return $country;
    }

    private function givenCustomer(Company $company, Country $country)
    {
        $customer = Customer::create($company, 'Customer', 'customer@example.com', '01234567890', '', $country, 'Rome', 'RM', '00100', 'Abbey Road, 1234, London', '');
        return $customer;
    }


    private function givenRecurrenceWithOptions($date, $repeats, $repeatEvery = 1, $occurrences = 0)
    {
        $country = $this->givenCountry();
        $company = $this->givenCompany($country);
        $customer = $this->givenCustomer($company, $country);

        $startAt = new \DateTime($date);
        $startAt->setTime(0, 0);

        $recurrence = $this->givenRecurrenceWith('Content', Recurrence::OUTGOING, $startAt, $repeats, $repeatEvery, $customer, $company);
        $recurrence->setOccurrences($occurrences);

        return $recurrence;
    }

    private function givenRecurrence()
    {
        return $this->givenRecurrenceWithOptions('2017-02-27', Recurrence::EVERYDAY, 1);
    }

    private function givenRecurrenceWith($content, $direction, $startAt, $repeats, $repeatEvery, Customer $customer, Company $company)
    {
        $recurrence = Recurrence::create($content, $direction, $startAt, $repeats, $repeatEvery, $customer, $company);
        return $recurrence;
    }

    private function calculateDate(\DateTime $date, $time)
    {
        return new \DateTime(sprintf('%s %s', $date->format('Y-m-d'), $time));
    }
}
