<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class DocumentTemplatePerCompany extends BaseDocumentTemplate
{
    /**
     * @var DocumentTemplate
     */
    private $documentTemplate;

    /** @var Company */
    private $company;

    /** @var ArrayCollection */
    private $documents;

    public static function create(DocumentTemplate $documentTemplate, Company $company)
    {
        $entity = new self();
        $entity->documentTemplate = $documentTemplate;
        $entity->copyDocumentTemplateDetails();
        $entity->company = $company;

        return $entity;
    }

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function copyDocumentTemplateDetails()
    {
        $this->setName($this->documentTemplate->getName());
        $this->setTextColor($this->documentTemplate->getTextColor());
        $this->setTableHeaderBackgroundColor($this->documentTemplate->getTableHeaderBackgroundColor());
        $this->setTableHeaderColor($this->documentTemplate->getTableHeaderColor());
        $this->setHeadingColor($this->documentTemplate->getHeadingColor());
        $this->setFontFamily($this->documentTemplate->getFontFamily());
        $this->setHeader($this->documentTemplate->getHeader());
        $this->setContent($this->documentTemplate->getContent());
        $this->setFooter($this->documentTemplate->getFooter());
    }

    public function getDocumentTemplate()
    {
        return $this->documentTemplate;
    }

    public function setDocumentTemplate(DocumentTemplate $documentTemplate)
    {
        $this->documentTemplate = $documentTemplate;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
        $this->company->addDocumentTemplatePerCompany($this);
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    public function addDocument(Document $document)
    {
        $this->documents->add($document);
    }

}