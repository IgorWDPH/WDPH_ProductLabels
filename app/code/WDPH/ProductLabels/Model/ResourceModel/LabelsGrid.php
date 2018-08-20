<?php
namespace WDPH\ProductLabels\Model\ResourceModel;


class LabelsGrid extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{	
	public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('wdph_productlabels', 'label_id');
	}
	
	protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
	{
		//$object->setData('store_ids', implode(',', $object->getData('store_ids')));
		return parent::_beforeSave($object);
	}
}
?>