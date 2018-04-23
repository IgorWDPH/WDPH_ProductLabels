<?php

namespace WDPH\ProductLabels\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
	public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();
		if(!$installer->tableExists('wdph_productlabels'))
		{
			$table = $installer->getConnection()->newTable($installer->getTable('wdph_productlabels'))
				->addColumn(
					'label_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Label ID'
				)
				->addColumn(
					'label_name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Label Name'
				)
				->addColumn(
					'label_attr',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Label Attribute'
				)
				->addColumn(
					'label_text',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Label Text'
				)		
				->addColumn(
					'label_status',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['nullable' => false]					
				)
				->addColumn(
					'show_on_product_view_page',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['nullable' => false]					
				)
				->addColumn(
					'show_on_product_list_page',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['nullable' => false]					
				)				
				->addColumn(
					'active_from',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					[],
					'Active From'
				)
				->addColumn(
					'active_to',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					[],
					'Active To'
				)
				->addColumn(
					'width_list',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Product List Label width'
				)
				->addColumn(
					'height_list',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Product List Label height'
				)
				->addColumn(
					'width_view',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Product View Label width'
				)
				->addColumn(
					'height_view',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Product View Label height'
				)
				->addColumn(
					'position_top',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Top position'
				)
				->addColumn(
					'position_bottom',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Bottom position'
				)
				->addColumn(
					'position_left',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Left position'
				)
				->addColumn(
					'position_right',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Right position'
				)
				->addColumn(
					'list_font_size',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Product List Label Font Size'
				)
				->addColumn(
					'view_font_size',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Product View Label Font Size'
				)
				->addColumn(
					'back_color',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Label Background Color'
				)
				->addColumn(
					'font_color',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Label Font Color'
				)
				->addColumn(
					'image',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'64k',
					[],
					'Label Background Image'
				)
				->addColumn(
					'custom_css',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'64k',
					[],
					'Label Custom Styles'
				)
				/*->addColumn(
					'created_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
					'Created At')*/				
				->setComment('Labels Table');
			$installer->getConnection()->createTable($table);
			//$installer->getConnection()->addIndex($installer->getTable('wdph_productlabels'), $setup->getIdxName($installer->getTable('wdph_productlabels_post'), ['label_status', 'label_text', 'show_on_product_view_page', 'show_on_product_list_page', 'active_from', 'active_to', 'width', 'height', 'back_color', 'font_color', 'image', 'custom_css'], \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT), ['label_status', 'label_text', 'show_on_product_view_page', 'show_on_product_list_page', 'active_from', 'active_to', 'width', 'height', 'back_color', 'font_color', 'image', 'custom_css'], \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT);
		}
		$installer->endSetup();
	}
}
?>