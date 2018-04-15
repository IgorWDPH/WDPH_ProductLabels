<?php
namespace WDPH\ProductLabels\Model\ResourceModel\LabelsGrid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'label_id';
	protected $_eventPrefix = 'wdph_productlabels_collection';
	protected $_eventObject = 'wdph_productlabels_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('WDPH\ProductLabels\Model\LabelsGrid', 'WDPH\ProductLabels\Model\ResourceModel\LabelsGrid');
	}

}
