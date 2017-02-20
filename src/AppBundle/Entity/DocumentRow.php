<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use AppBundle\Exception\NonPositiveAmountException;
use AppBundle\Exception\NonPositiveQuantityException;
use Symfony\Component\Validator\Constraints as Assert;

class DocumentRow
{
    const DECIMALS = 2;

    /** @var int */
    private $id;

    /** @var Document */
    private $document;

    /**
     * @Assert\NotBlank(message="error.empty_position")
     *
     * @var int
     */
    private $position = 0;

    /**
     * @Assert\NotBlank(message="error.empty_title")
     *
     * @var string
     */
    private $title;

    /** @var string */
    private $description;

    /**
     * @Assert\NotBlank(message="error.empty_unit_price")
     *
     * @var float
     */
    private $unitPrice;

    /** @var float */
    private $quantity = 1;

    /** @var TaxRate */
    private $taxRate;

    /** @var float */
    private $discount = 0;
    
    /** @var bool */
    private $discountPercent = false;

    /** @var float */
    private $netCost = 0;

    /** @var float */
    private $taxes = 0;

    /** @var float */
    private $grossCost = 0;

    /**
     * @var Product
     */
    private $linkedProduct;

    /**
     * @var Service
     */
    private $linkedService;

    public static function create($id, $position, $title, $description, $unitPrice, $quantity, TaxRate $taxRate, $discount, $discountPercent = false)
    {
        $entity = new self();
        $entity->id = $id;
        $entity->position = $position;
        $entity->title = $title;
        $entity->description = $description;
        $entity->unitPrice = $unitPrice;
        $entity->quantity = $quantity;
        $entity->discount = $discount;
        $entity->discountPercent = $discountPercent;
        $entity->taxRate = $taxRate;

        if ($entity->unitPrice <= 0) {
            throw new NonPositiveAmountException();
        }

        if ($entity->quantity <= 0) {
            throw new NonPositiveQuantityException();
        }

        $entity->calculateTotals();

        return $entity;
    }

    public static function createEmpty()
    {
        $entity = new self();
        $entity->position = 0;
        $entity->quantity = 1;
        $entity->discount = 0;

        return $entity;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setDocument(Document $document)
    {
        $this->document = $document;

        $document->addRow($this);
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getTaxRate()
    {
        return $this->taxRate;
    }

    public function setTaxRate(TaxRate $taxRate)
    {
        $this->taxRate = $taxRate;
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function isDiscountPercent()
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent($discountPercent)
    {
        $this->discountPercent = $discountPercent;
    }

    public function getNetCost()
    {
        return $this->netCost;
    }

    public function setNetCost($netCost)
    {
        $this->netCost = $netCost;
    }

    public function getTaxes()
    {
        return $this->taxes;
    }

    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;
    }

    public function getGrossCost()
    {
        return $this->grossCost;
    }

    public function setGrossCost($grossCost)
    {
        $this->grossCost = $grossCost;
    }

    public function getLinkedProduct()
    {
        return $this->linkedProduct;
    }

    public function hasLinkedProduct()
    {
        return $this->linkedProduct != null;
    }

    public function setLinkedProduct(Product $linkedProduct = null)
    {
        $this->linkedProduct = $linkedProduct;
    }

    public function getLinkedService()
    {
        return $this->linkedService;
    }

    public function hasLinkedService()
    {
        return $this->linkedService != null;
    }

    public function setLinkedService(Service $linkedService = null)
    {
        $this->linkedService = $linkedService;
    }

    public function calculateTotals()
    {
        $netCost = ($this->unitPrice - $this->calculateDiscount()) * $this->quantity;
        $taxes = $netCost * ($this->getTaxRateAmount() / 100);

        $this->netCost = round($netCost, self::DECIMALS);
        $this->taxes = round($taxes, self::DECIMALS);
        $this->grossCost = round($netCost + $taxes, self::DECIMALS);

//        dump(sprintf('%s - Row: ' . $this->getTitle() . ', net: %s, taxes: %s, gross: %s', microtime(), $this->netCost, $this->taxes, $this->grossCost));die;
    }

    public function getTaxRateAmount()
    {
        if ($this->taxRate) {
            return $this->taxRate->getAmount();
        }

        return 0;
    }

    public function copy()
    {
        return clone $this;
    }

    private function calculateDiscount()
    {
        if($this->discountPercent) {
            return ($this->unitPrice * $this->discount) / 100;
        }

        return $this->discount;
    }
}
