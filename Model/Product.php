<?php
declare(strict_types=1);

namespace Elgentos\ReorderAlternatives\Model;

use Elgentos\ReorderAlternatives\Model\Product\Link;
use Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection;

class Product extends \Magento\Catalog\Model\Product {
    const string LINK_TYPE = 'alternatives';

    /**
     * @return mixed|null
     */
    public function getAlternativeProducts() {
        if (!$this->hasData('alternative_products')) {
            $products = [];
            foreach ($this->getAlternativeProductCollection() as $product) {
                $products[] = $product;
            }
            $this->setData('alternative_products', $products);
        }
        return $this->getData('alternative_products');
    }

    /**
     * @return Collection
     */
    public function getAlternativeProductCollection(): Collection
    {
        return $this->getLinkInstance()
            ->setLinkTypeId(Link::LINK_TYPE_ALTERNATIVES_ID)
            ->getProductCollection()
            ->setIsStrongMode()
            ->setProduct($this);
    }
}
