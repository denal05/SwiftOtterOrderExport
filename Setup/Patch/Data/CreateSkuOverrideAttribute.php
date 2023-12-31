<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Setup\Patch\Data;

use Denal05\SwiftOtterOrderExport\Attributes;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
* Patch is mechanism, that allows to do atomic upgrade data changes
*/
class CreateSkuOverrideAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            Attributes::SKU_OVERRIDE_ATTRIBUTE,
            [
                'apply_to' => 'simple,virtual',
                'comparable' => false,
                'filterable' => false,
                'filterable_in_grid' => false,
                'filterable_in_search' => false,
                'html_allowed_on_front' => false,
                'input' => 'text',
                'label' => 'SKU Override',
                'position' => 0,
                'required' => false,
                'scope' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'searchable' => false,
                'sort_order' => 70,
                'type' => 'varchar',
                'used_for_promo_rules' => false,
                'used_for_sort_by' => false,
                'used_in_grid' => false,
                'used_in_product_listing' => true,
                'user_defined' => false,
                'visible' => false,
                'visible_in_advanced_search' => false,
                'visible_in_grid' => false,
                'visible_on_front' => false,
                'wysiwyg_enabled' => false,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
