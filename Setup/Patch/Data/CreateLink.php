<?php

namespace Elgentos\ReorderAlternatives\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class CreateLink implements DataPatchInterface
{
    private $moduleDataSetup;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $setup->getConnection()->insertForce(
            $setup->getTable('catalog_product_link_type'),
            [
                'link_type_id' => \Elgentos\ReorderAlternatives\Model\Product\Link::LINK_TYPE_ALTERNATIVES_ID,
                'code' => \Elgentos\ReorderAlternatives\Model\Product::LINK_TYPE
            ]
        );
        $setup->getConnection()->insertMultiple(
            $setup->getTable('catalog_product_link_attribute'),
            [
                [
                    'link_type_id' => \Elgentos\ReorderAlternatives\Model\Product\Link::LINK_TYPE_ALTERNATIVES_ID,
                    'product_link_attribute_code' => 'position',
                    'data_type' => 'int'
                ]
            ]
        );
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
