<?php
declare(strict_types=1);

namespace Elgentos\ReorderAlternatives\Plugin;

use Elgentos\ReorderAlternatives\Model\ProductFactory;

class UpdateToAlternativesLinkModel {
    protected $catalogModel;

    /**
     * Constructor.
     *
     * @param ProductFactory $catalogModel
     */
    public function __construct(ProductFactory $catalogModel) {
        $this->catalogModel = $catalogModel;
    }

    public function beforeGetLinkedProducts($subject, $product) {
        return [$this->catalogModel->create()->load($product->getId())];
    }
}
