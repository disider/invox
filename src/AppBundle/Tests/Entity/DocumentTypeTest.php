<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Tests\Entity;

use AppBundle\Model\DocumentType;

class DocumentTypeTest extends \PhpUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testGetTypes()
    {
        $types = [
            DocumentType::QUOTE,
            DocumentType::ORDER,
            DocumentType::INVOICE,
            DocumentType::CREDIT_NOTE,
            DocumentType::RECEIPT,
            DocumentType::DELIVERY_NOTE,
        ];

        $this->assertThat(DocumentType::getAll(), $this->equalTo($types));
    }
}
