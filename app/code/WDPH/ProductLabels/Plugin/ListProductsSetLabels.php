<?php

namespace WDPH\ProductLabels\Plugin;

class ListProductsSetLabels
{	
	protected $labelsMainHelper;	
	
	public function __construct(\WDPH\ProductLabels\Helper\Data $labelsMainHelper)
	{
		$this->labelsMainHelper = $labelsMainHelper;		
    }
	
    public function aroundGetImage($subject, $proceed, $product, $imageId, $attributes = [])
    {
		$result = $proceed($product, $imageId, $attributes = []);
		if(!$product->getId())
		{
			return $result;
		}
		$labels = $this->labelsMainHelper->getProductLabels($product, 'show_on_product_list_page');
		$result->setData('wdph_labels', $labels);
        return $result;
    }
}
?>