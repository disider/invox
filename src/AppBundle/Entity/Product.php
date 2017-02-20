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

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @JMS\ExclusionPolicy("all")
 */
class Product extends Item
{

    /**
     * @var boolean
     */
    private $enabledInWarehouse = false;

    /**
     * @var float
     */
    private $initialStock = 0;

    /**
     * @var float
     */
    private $currentStock = 0;

    public static function create(Company $company, $name, $code, $enabledInWarehouse = false, $initialStock = 0)
    {
        $entity = new self();
        $entity->company = $company;
        $entity->name = $name;
        $entity->code = $code;
        $entity->enabledInWarehouse = $enabledInWarehouse;
        $entity->initialStock = $initialStock;
        $entity->currentStock = $initialStock;

        return $entity;
    }

    public function isEnabledInWarehouse()
    {
        return $this->enabledInWarehouse;
    }

    public function setEnabledInWarehouse($enabledInWarehouse)
    {
        $this->enabledInWarehouse = $enabledInWarehouse;
    }

    public function getInitialStock()
    {
        return $this->initialStock;
    }

    public function setInitialStock($initialStock)
    {
        $this->initialStock = $initialStock;
    }

    public function getCurrentStock()
    {
        return $this->currentStock;
    }

    public function setCurrentStock($currentStock)
    {
        $this->currentStock = $currentStock;
    }

    public function updateStock($stockBalance)
    {
        $this->currentStock += $stockBalance;
    }

    public function getAttachmentsUploadDir()
    {
        return $this->getUploadDir() . sprintf('/products/%s/attachments', $this->getId());
    }

    protected function buildAttachment()
    {
        return new ProductAttachment();
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("type")
     *
     * @return string
     */
    public function serializeType()
    {
        return 'product';
    }

    protected function buildTag()
    {
        return new ProductTag();
    }
}
