<?php
namespace WDPH\ProductLabels\Model\ResourceModel\LabelsGrid;
 
class CollectionFactory
{    
    protected $_objectManager = null;    
    protected $_instanceName = null; 
    
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager,
								$instanceName = '\WDPH\ProductLabels\Model\ResourceModel\LabelsGrid\Collection')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    } 
    
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}