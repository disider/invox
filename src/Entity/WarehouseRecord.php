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

use App\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @CustomAssert\ValidWarehouseRecord()
 */
class WarehouseRecord
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Product
     */
    private $product;

    /**
     * @Assert\Type(type="float", message="error.invalid_quantity")
     * @var float
     */
    private $loadQuantity;

    /**
     * @Assert\Type(type="float", message="error.invalid_quantity")
     * @var float
     */
    private $unloadQuantity;

    /**
     * @var float
     */
    private $purchasePrice;

    /**
     * @var float
     */
    private $sellPrice;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $description;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public static function create(Product $product, $loadQuantity, $unloadQuantity, $purchasePrice, $sellPrice)
    {
        $entity = new self();

        $entity->product = $product;
        $entity->loadQuantity = $loadQuantity;
        $entity->unloadQuantity = $unloadQuantity;
        $entity->purchasePrice = $purchasePrice;
        $entity->sellPrice = $sellPrice;

        return $entity;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function getLoadQuantity()
    {
        return $this->loadQuantity;
    }

    public function setLoadQuantity($loadQuantity)
    {
        $this->loadQuantity = $loadQuantity;
    }

    public function getUnloadQuantity()
    {
        return $this->unloadQuantity;
    }

    public function setUnloadQuantity($unloadQuantity)
    {
        $this->unloadQuantity = $unloadQuantity;
    }

    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;
    }

    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getStockBalance()
    {
        return $this->loadQuantity - $this->unloadQuantity;
    }

}