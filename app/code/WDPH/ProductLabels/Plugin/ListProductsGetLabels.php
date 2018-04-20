<?php

namespace WDPH\ProductLabels\Plugin;

class ListProductsGetLabels
{
	protected $labelsModelFactory;	
	
    public function afterToHtml($subject, $result)
    {		
        return $result . $subject->getData('wdph_labels');
    }
}
?>