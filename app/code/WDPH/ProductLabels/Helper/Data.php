<?php
namespace WDPH\ProductLabels\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    protected $storeManager;
    protected $objectManager;
	protected $labelsModelFactory;
	protected $eavAttribute;
	
	const DATA_TIME_FORMAT = 'Y-m-d g:i:s';
	const XML_PATH_MEGAMENU = 'wdph_productlabels_main/';
	const RANDOM_CHARLIST = 'abcdefghijklmnopqrstuvwxyz';
	const MAIN_LABEL_CLASS = 'wdph-product-label';
	const LABELS_MEDIA_DIR = 'wdph_labels';	

    public function __construct(Context $context,
								ObjectManagerInterface $objectManager,
								StoreManagerInterface $storeManager,
								\Magento\Eav\Model\Config $eavAttribute,								
								\WDPH\ProductLabels\Model\ResourceModel\LabelsGrid\CollectionFactory $labelsModelFactory)
	{
        $this->objectManager = $objectManager;
        $this->storeManager  = $storeManager;
		$this->eavAttribute = $eavAttribute;
		$this->labelsModelFactory = $labelsModelFactory;
        parent::__construct($context);
    }
	
	public function getLabelsMediaDir()
	{
		return self::LABELS_MEDIA_DIR;
	}
	
	public function getConfig($config_path, $storeCode = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_MEGAMENU . $config_path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeCode);
    }
	
	public function getProductLabels($product, $target)
	{
		$collection = $this->labelsModelFactory->create();
		$labels = '';
		$styles = '';
		foreach($collection as $item)
		{			
			if(!$item['label_status'] || !$item[$target] || !$item['label_attr'])
			{
				continue;
			}
			if($item['active_from'] || $item['active_to'])
			{				
				$current = time();
				$from = $current;
				$to = $current;
				if($item['active_from'])
				{
					$from = date_create_from_format(self::DATA_TIME_FORMAT, $item['active_from'])->format("U") . '<br>';
				}				
				if($item['active_to'])
				{
					$to = date_create_from_format(self::DATA_TIME_FORMAT, $item['active_to'])->format("U") . '<br>';
				}
				if($from < $to && ($current < $from || $current > $to))
				{
					continue;
				}
			}
			$attribute = $this->eavAttribute->getAttribute('catalog_product', $item['label_attr']);
			if(!$attribute->getId())
			{
				continue;
			}
			$id = \Zend\Math\Rand::getString(32, self::RANDOM_CHARLIST, true);			
			$customLabelStyles = '#' . $id . ' {' . $item['custom_css'] . '}';
			if($attribute->getFrontendInput() == 'select')
			{
				$attr_value = $product->getResource()->getAttribute($item['label_attr']);
				if(!$attr_value || $attr_value->getFrontend()->getValue($product) == 'No')
				{
					continue;
				}
				$labels .= '<div id="' . $id . '" class="' . self::MAIN_LABEL_CLASS . '" ' . $this->getLabelStyles($item, $target) . '>' . $attr_value->getFrontend()->getValue($product) . '</div>';
				$styles .= $customLabelStyles;
			}
			elseif($attribute->getFrontendInput() == 'boolean')
			{
				$attr_value = $product->getResource()->getAttribute($item['label_attr']);
				if(!$attr_value || $attr_value->getFrontend()->getValue($product) == 'No' || !trim($item->getData('label_text')))
				{
					continue;
				}
				$labels .= '<div id="' . $id . '" class="' . self::MAIN_LABEL_CLASS . '"' . $this->getLabelStyles($item, $target) . '>' . $item->getData('label_text') . '</div>';
				$styles .= $customLabelStyles;				
			}
		}
		$labels = $labels . '<style type="text/css">' . '.' . self::MAIN_LABEL_CLASS . ' {' . $this->getConfig('general/common_custom_css') . '} ' . $styles . '</style>';
		return $labels;
	}
	
	public function getLabelStyles($labelItem, $target)
	{
		$defaultConfigs = $this->getConfig('general');
		$result = 'style="';
		$additionalAttr = '';
		//Background Image
		if($labelItem['image'])
		{
			$mediaDirectory = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
			$result .= 'background-image: url(' . $mediaDirectory . $labelItem['image'] . ');';
		}		
		//Position Bottom/Top
		if(trim($labelItem['position_bottom']))
		{
			$additionalAttr .= 'bottom="' . $labelItem['position_bottom'] . '"';
			$result .= 'bottom: ' . $labelItem['position_bottom'] . ';';
		}
		elseif(trim($labelItem['position_top']))
		{
			$additionalAttr .= 'top ';
			$result .= 'top: ' . $labelItem['position_top'] . ';';
		}
		elseif(trim($defaultConfigs['default_bottom']))
		{
			$additionalAttr .= 'bottom="' . $labelItem['position_bottom'] . '"';
			$result .= 'bottom: ' . $defaultConfigs['default_bottom'] . ';';
		}
		elseif(trim($defaultConfigs['default_top']))
		{
			$additionalAttr .= 'top ';
			$result .= 'top: ' . $defaultConfigs['default_top'] . ';';
		}
		else
		{
			$additionalAttr .= 'top ';
			$result .= 'top: 0;';
		}
		//Position Right/Left
		if(trim($labelItem['position_right']))
		{
			$additionalAttr .= 'right ';
			$result .= 'right: ' . $labelItem['position_right'] . ';';
		}
		elseif(trim($labelItem['position_left']))
		{
			$additionalAttr .= 'left ';
			$result .= 'left: ' . $labelItem['position_left'] . ';';
		}
		elseif(trim($defaultConfigs['default_right']))
		{
			$additionalAttr .= 'right ';
			$result .= 'right: ' . $defaultConfigs['default_right'] . ';';
		}
		elseif(trim($defaultConfigs['default_left']))
		{
			$additionalAttr .= 'left ';
			$result .= 'left: ' . $defaultConfigs['default_left'] . ';';
		}
		else
		{
			$additionalAttr .= 'left ';
			$result .= 'left: 0;';
		}
		//Font size for list/view page
		if($target == 'show_on_product_list_page')
		{
			//Width
			if(trim($labelItem['width_list']))
			{
				$result .= 'width: ' . $labelItem['width_list'] . ';';
			}
			elseif(trim($defaultConfigs['default_width_list']))
			{
				$result .= 'width: ' . $defaultConfigs['default_width_list'] . ';';
			}
			//Height
			if(trim($labelItem['height_list']))
			{
				$result .= 'height: ' . $labelItem['height_list'] . ';';
				$result .= 'line-height:' . $labelItem['height_list'] . ';';
			}
			elseif(trim($defaultConfigs['default_height_list']))
			{
				$result .= 'height: ' . $defaultConfigs['default_height_list'] . ';';
				$result .= 'line-height:' . $defaultConfigs['default_height_list'] . ';';
			}
			//Font size
			if(trim($labelItem['list_font_size']))
			{
				$result .= 'font-size: ' . $labelItem['list_font_size'] . ';';
			}
			elseif(trim($defaultConfigs['default_list_font_size']))
			{
				$result .= 'font-size: ' . $defaultConfigs['default_list_font_size'] . ';';
			}
		}
		elseif($target == 'show_on_product_view_page')
		{
			//Width
			if(trim($labelItem['width_view']))
			{
				$result .= 'width: ' . $labelItem['width_view'] . ';';
			}
			elseif(trim($defaultConfigs['default_width_view']))
			{
				$result .= 'width: ' . $defaultConfigs['default_width_view'] . ';';
			}
			//Height
			if(trim($labelItem['height_view']))
			{
				$result .= 'height: ' . $labelItem['height_view'] . ';';
				$result .= 'line-height:' . $labelItem['height_view'] . ';';
			}
			elseif(trim($defaultConfigs['default_height_view']))
			{
				$result .= 'height: ' . $defaultConfigs['default_height_view'] . ';';
				$result .= 'line-height:' . $defaultConfigs['default_height_view'] . ';';
			}
			//Font size
			if(trim($labelItem['view_font_size']))
			{
				$result .= 'font-size: ' . $labelItem['view_font_size'] . ';';
			}
			elseif(trim($defaultConfigs['default_view_font_size']))
			{
				$result .= 'font-size: ' . $defaultConfigs['default_view_font_size'] . ';';
			}
		}
		//Background color
		if(trim($labelItem['back_color']))
		{
			$result .= 'background-color: ' . $labelItem['back_color'] . ';';
		}
		elseif(trim($defaultConfigs['default_background_color']))
		{
			$result .= 'background-color: ' . $defaultConfigs['default_background_color'] . ';';
		}
		//Font color
		if(trim($labelItem['font_color']))
		{
			$result .= 'color: ' . $labelItem['font_color'] . ';';
		}
		elseif(trim($defaultConfigs['default_font_color']))
		{
			$result .= 'color: ' . $defaultConfigs['default_font_color'] . ';';
		}		 
		$result = $additionalAttr . $result . '"';
		return $result;
	}
}
?>