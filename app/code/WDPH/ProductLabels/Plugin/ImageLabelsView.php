<?php

namespace WDPH\ProductLabels\Plugin;

class ImageLabelsView
{
	protected $labelsModelFactory;
	
	public function __construct(\WDPH\ProductLabels\Model\ResourceModel\LabelsGrid\CollectionFactory $labelsModelFactory)
	{
        $this->labelsModelFactory = $labelsModelFactory;
    }
	
    public function afterToHtml($subject, $result)
    {
		if(get_class($subject) != 'Magento\Catalog\Block\Product\View\Gallery\Interceptor')
		{
			return $result;
		}
		$collection = $this->labelsModelFactory->create();
        $labels = '';
		foreach($collection as $item)
		{
			$labels .= '<div class="wdph-product-label">' . $item->getData('label_text') . '</div>';
		}
        return $result . $labels;
    }
}