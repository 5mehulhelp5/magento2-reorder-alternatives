<?php
declare(strict_types=1);

namespace Elgentos\ReorderAlternatives\Model\Product;

class Link extends \Magento\Catalog\Model\Product\Link {
    const int LINK_TYPE_ALTERNATIVES_ID = 7;

    /**
     * @return int
     */
    public function getLinkTypeId(): int
    {
        return static::LINK_TYPE_ALTERNATIVES_ID;
    }
}
