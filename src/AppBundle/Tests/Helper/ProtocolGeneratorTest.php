<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Tests\Helper;

use AppBundle\Entity\Company;
use AppBundle\Entity\Country;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentTemplate;
use AppBundle\Entity\DocumentTemplatePerCompany;
use AppBundle\Entity\User;
use AppBundle\Helper\ProtocolGenerator;
use AppBundle\Model\DocumentType;
use AppBundle\Tests\RepositoryTestCase;
use Doctrine\ORM\EntityManager;

class ProtocolGeneratorTest extends RepositoryTestCase
{
    /** @var EntityManager */
    private $em;

    /** @var ProtocolGenerator */
    private $generator;

    /** @var Country */
    private $country;

    /**
     * @before
     */
    public function setUp()
    {
        $this->em = $this->getService('doctrine.orm.entity_manager');
        $this->generator = new ProtocolGenerator($this->em);
        $this->country = $this->givenCountry();
    }

    /**
     * @test
     */
    public function testGenerateFirstForDocuments()
    {
        $company = $this->givenCompany();

        $this->assertThat($this->generator->generate('AppBundle:Document', $company, 2016), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function testGenerateFirstForPettyCashNotes()
    {
        $company = $this->givenCompany();

        $this->assertThat($this->generator->generate('AppBundle:PettyCashNote', $company, 2016), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function testGenerateNext()
    {
        $em = $this->getService('doctrine.orm.entity_manager');
        $generator = new ProtocolGenerator($em);

        $company = $this->givenCompany();
        $this->givenDocument($company);

        $this->assertThat($generator->generate('AppBundle:Document', $company, 2016), $this->equalTo(2));
    }

    /**
     * @test
     */
    public function whenGeneratingForAnotherCompany_thenGenerateFirst()
    {
        $em = $this->getService('doctrine.orm.entity_manager');
        $generator = new ProtocolGenerator($em);

        $company1 = $this->givenCompany();
        $company2 = $this->givenCompany();
        $this->givenDocument($company1);

        $this->assertThat($generator->generate('AppBundle:Document', $company2, 2016), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function whenGeneratingForAnotherYear_thenGenerateFirst()
    {
        $em = $this->getService('doctrine.orm.entity_manager');
        $generator = new ProtocolGenerator($em);

        $company = $this->givenCompany();
        $this->givenDocument($company, 2015);

        $this->assertThat($generator->generate('AppBundle:Document', $company, 2016), $this->equalTo(1));
    }

    private function givenCompany()
    {
        $user = $this->givenUser();

        $company = Company::create($this->country, $user, 'Acme', 'Address', '01234567890');

        return $this->save($company);
    }

    private function givenCountry()
    {
        $country = Country::create('Italy');

        return $this->save($country);
    }

    private function givenUser()
    {
        $user = User::create('user@example.com', 'password', '1234');

        return $this->save($user);
    }

    private function givenDocument(Company $company, $year = 2016)
    {
        $template = DocumentTemplate::create('Template', '', '', '', '', '', '', '', '');
        $this->save($template);

        $templatePerCompany = new DocumentTemplatePerCompany();
        $templatePerCompany->setDocumentTemplate($template);
        $templatePerCompany->copyDocumentTemplateDetails();
        $templatePerCompany->setCompany($company);
        $this->save($templatePerCompany);

        $document = Document::create(DocumentType::QUOTE, Document::OUTGOING, $company, 1, $year, new \DateTime());
        $document->setCustomerCountry($this->country);
        $document->setDocumentTemplate($templatePerCompany);

        return $this->save($document);
    }

    private function save($object)
    {
        $this->em->persist($object);
        $this->em->flush();

        return $object;
    }
}
