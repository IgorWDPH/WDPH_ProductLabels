<?php
namespace WDPH\ProductLabels\Model\UiForm;
 
use Webkul\UiForm\Model\ResourceModel\Employee\CollectionFactory;
 
class ViewFormDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
	protected $_loadedData;
		
    public function __construct(\WDPH\ProductLabels\Model\ResourceModel\LabelsGrid\CollectionFactory $labelsCollectionFactory,
								$name,
								$primaryFieldName,
								$requestFieldName,								
								array $meta = [],
								array $data = [])
	{
        $this->collection = $labelsCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    
    public function getData()
    {
        if(isset($this->_loadedData))
		{
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $label)
		{
            $this->_loadedData[$label->getId()] = $label->getData();
        }
        return $this->_loadedData;
    }
}
?>