<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Features\Context;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttachment;
use AppBundle\Entity\WarehouseRecord;
use Behat\Gherkin\Node\TableNode;

class ProductContext extends BaseMinkContext
{
    /**
     * @Given /^there is a product:$/
     * @Given /^there are products:$/
     */
    public function thereAreEntities(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $values['company'],
                $values['name'],
                $this->getValue($values, 'code', $values['name']),
                $this->getBoolValue($values, 'enabledInWarehouse', false),
                $this->getFloatValue($values, 'initialStock', 0),
                $this->getFloatValue($values, 'unitPrice'),
                $this->getValue($values, 'tags', '')
            );

            $taxRateAmount = $this->getFloatValue($values, 'taxRate');

            if ($taxRateAmount) {
                $taxRate = $this->getTaxRateRepository()->findOneByAmount($taxRateAmount);

                if (!$taxRate) {
                    throw new \InvalidArgumentException('No tax rate found with amount: ' . $taxRateAmount);
                }

                $entity->setTaxRate($taxRate);
            }

            $this->getProductRepository()->save($entity);
        }
    }

    private function buildEntity($companyName, $name, $code, $enabledInWarehouse, $initialStock, $unitPrice, $tags)
    {
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        $product = Product::create($company, $name, $code, $enabledInWarehouse, $initialStock);
        $product->setUnitPrice($unitPrice);
        $product->setTags($tags);

        return $product;
    }

    /**
     * @Given /^there are warehouse records:$/
     */
    public function thereAreWarehouseRecords(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildWarehouseEntity(
                $values['product'],
                $values['loadQuantity'],
                $values['unloadQuantity'],
                $this->getValue($values, 'purchasePrice', 10),
                $this->getValue($values, 'sellPrice', 20)
            );

            $this->getProductRepository()->save($entity);
        }
    }

    private function buildWarehouseEntity($productName, $loadQuantity, $unloadQuantity, $purchasePrice, $sellPrice)
    {
        $product = $this->getProductRepository()->findOneByName($productName);

        $record = new WarehouseRecord();

        return WarehouseRecord::create($product, $loadQuantity, $unloadQuantity, $purchasePrice, $sellPrice);
    }

    /**
     * @Given /^there is a product attachment:$/
     */
    public function thereIsAProductAttachment(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            /** @var Product $product */
            $product = $this->getProductRepository()->findOneByName($values['product']);

            $fileName = $values['fileName'];
            $fileUrl = $values['fileUrl'];

            $this->buildAttachment(
                $product,
                $fileName,
                $fileUrl,
                ProductAttachment::class
            );

            $this->getProductRepository()->save($product);
        }
    }
}
