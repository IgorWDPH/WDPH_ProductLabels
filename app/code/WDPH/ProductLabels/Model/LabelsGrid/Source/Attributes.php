<?php
namespace WDPH\ProductLabels\Model\LabelsGrid\Source;
 
class Attributes implements \Magento\Framework\Data\OptionSourceInterface
{  
	protected $_attributeFactory;
 
	public function __construct(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory)
	{
		$this->_attributeFactory = $attributeFactory;
	}
 
	public function toOptionArray()
	{		
		return $this->getProductAttributesArray();
	}
	
	protected function getProductAttributesArray()
	{
		$attributes = $this->_attributeFactory->getCollection()
			->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4)
			->addFieldToFilter('frontend_input', ['in' => ['select', 'boolean']])
			->setOrder('frontend_label','ASC');			
		if(empty($attributes)) return false;
		$result = array();
		$result[] = ['label' => 'No attribute', 'value' => ''];
		foreach($attributes as $attribute)
		{			
			$result[] = ['label' => $attribute->getFrontendLabel(), 'value' => $attribute->getAttributeCode()];
		}
		return $result;
	}
}
?>