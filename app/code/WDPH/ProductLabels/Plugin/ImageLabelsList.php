<?php

namespace WDPH\ProductLabels\Plugin;

class ImageLabelsList
{
	protected $labelsModelFactory;
	
	public function __construct(\WDPH\ProductLabels\Model\ResourceModel\LabelsGrid\CollectionFactory $labelsModelFactory)
	{        
        $this->labelsModelFactory = $labelsModelFactory;        
    }
	
    public function aroundGetImage($subject, $proceed, $product, $imageId, $attributes = [])
    {
		$result = $proceed($product, $imageId, $attributes = []);
		$collection = $this->labelsModelFactory->create();        
		foreach($collection as $item)
		{
			//$result .= '<div class="wdph-product-label">' . $item->getData('label_text') . '</div>';
		}		
        return $result;
    }
}
?>