<?php
namespace WDPH\ProductLabels\Model\LabelsGrid\Source;
 
class Status implements \Magento\Framework\Data\OptionSourceInterface
{  
	protected $_grid;
 
	public function __construct(\WDPH\ProductLabels\Model\LabelsGrid $grid)
	{
	  $this->_grid = $grid;
	}
 
	public function toOptionArray()
	{
		$options[] = ['label' => '', 'value' => ''];
		$availableOptions = $this->_grid->getAvailableStatuses();
		foreach($availableOptions as $key => $value)
		{
			$options[] = [
				'label' => $value,
				'value' => $key,
			];
		}
		return $options;
	}
}
?>