<?php

namespace WDPH\ProductLabels\Plugin;

class ViewProductsGetLabels
{	
	protected $labelsMainHelper;
	
	public function __construct(\WDPH\ProductLabels\Helper\Data $labelsMainHelper)
	{
        $this->labelsMainHelper = $labelsMainHelper;		
    }
	
    public function afterToHtml($subject, $result)
    {	
		if(get_class($subject) != 'Magento\Catalog\Block\Product\View\Gallery\Interceptor')
		{
			return $result;
		}
		$product = $subject->getProduct();
		if(!$product->getId())
		{
			return $result;
		}
		$labels = $this->labelsMainHelper->getProductLabels($product, 'show_on_product_view_page');		
        return $result . $labels;
    }
}