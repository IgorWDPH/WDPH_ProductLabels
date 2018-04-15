<?php
namespace WDPH\ProductLabels\Model;

class LabelsGrid extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	const SHOWON_ENABLED = 1;
	const SHOWON_DISABLED = 0;
	const CACHE_TAG = 'wdph_productlabels';
	protected $_cacheTag = 'wdph_productlabels';
	protected $_eventPrefix = 'wdph_productlabels';

	protected function _construct()
	{
		$this->_init('WDPH\ProductLabels\Model\ResourceModel\LabelsGrid');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getAvailableStatuses()
	{
		return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
	}
	
	public function getAvailableShowon()
	{
		return [self::SHOWON_ENABLED => __('Yes'), self::SHOWON_DISABLED => __('No')];
	}
}
?>