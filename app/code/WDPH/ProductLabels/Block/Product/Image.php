<?php
namespace WDPH\ProductLabels\Block\Product;

class Image extends \Magento\Catalog\Block\Product\Image
{	
	public function __construct(\Magento\Framework\View\Element\Template\Context $context, array $data = [])
	{		
        parent::__construct($context, $data);
    }
	
	public function getProduct()
	{
		return $this->imageHelper;
	}
}
?>