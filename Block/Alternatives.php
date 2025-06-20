<?php
declare(strict_types=1);

namespace Elgentos\ReorderAlternatives\Block;

use Elgentos\ReorderAlternatives\Model\Product\Link;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable as ConfigurableResource;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Link\Product\CollectionFactory as LinkProductCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\OrderRepository;

class Alternatives extends Template
{
    public function __construct(
        Template\Context $context,
        private readonly ProductRepository $productRepository,
        private readonly ConfigurableResource $configurableResource,
        private readonly ProductCollectionFactory $productCollectionFactory,
        private readonly LinkProductCollectionFactory $linkProductCollectionFactory,
        private readonly OrderRepository $orderRepository,
        private readonly Link $linkModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getNonSalableProducts()
    {
        $nonSalableProductIds = explode(',', $this->getRequest()->getParam('nonSalableProducts'));
        return $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $nonSalableProductIds]);
    }

    public function getSalableProducts()
    {
        $salableProducts = [];
        $nonSalableProductIds = explode(',', $this->getRequest()->getParam('nonSalableProducts'));
        $order = $this->orderRepository->get($this->getRequest()->getParam('orderId'));
        foreach ($order->getItems() as $item) {
            if (!in_array($item->getProductId(), $nonSalableProductIds)) {
                $salableProducts[] = $item->getProductId();
            }
        }
        return $salableProducts;
    }

    public function getAlternativeProducts(): array
    {
        $alternativeProducts = [];

        foreach ($this->getNonSalableProducts() as $nonSalableProduct) {
            try {
                $configuredAlternatives = $this->getConfiguredAlternativeProducts($nonSalableProduct);

                if (empty($configuredAlternatives) && in_array($nonSalableProduct->getTypeId(), ['simple', 'virtual'], true)) {
                    $parentIds = $this->getParentProductIds($nonSalableProduct);
                    foreach ($parentIds as $parentId) {
                        try {
                            $parentProduct = $this->productRepository->getById($parentId);
                            $configuredAlternatives = $this->getConfiguredAlternativeProducts($parentProduct);
                            if (!empty($configuredAlternatives)) {
                                break;
                            }
                        } catch (NoSuchEntityException | InputException $e) {
                            continue;
                        }
                    }
                }

                foreach ($configuredAlternatives as $crossSellProduct) {
                    $crossSellProduct = $crossSellProduct->load($crossSellProduct->getId());
                }

                if ($configuredAlternatives) {
                    $alternativeProducts[$nonSalableProduct->getId()] = $configuredAlternatives;
                }
            } catch (NoSuchEntityException | InputException $e) {
                continue;
            }
        }

        return $alternativeProducts;
    }

    private function getParentProductIds(Product $product): array
    {
        return $this->configurableResource->getParentIdsByChild($product->getId()) ?? [];
    }

    /**
     * @param mixed $nonSalableProduct
     *
     * @return array
     */
    private function getConfiguredAlternativeProducts(mixed $nonSalableProduct)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection $linkProductCollection */
        $linkProductCollection = $this->linkProductCollectionFactory->create();
        return $linkProductCollection
            ->setLinkModel($this->linkModel)
            ->setProduct($nonSalableProduct)
            ->getItems();
    }
}
