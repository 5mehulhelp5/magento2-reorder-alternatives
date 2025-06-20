<?php
namespace Elgentos\ReorderAlternatives\Model\ProductLink\CollectionProvider;

class AlternativeProducts {
    public function getLinkedProducts($product) {
        return $product->getAlternativeProducts();
    }
}
