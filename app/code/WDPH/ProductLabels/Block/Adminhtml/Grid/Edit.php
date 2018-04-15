<?php 
namespace WDPH\ProductLabels\Block\Adminhtml\LabelsGrid;
 
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{    
    protected $_coreRegistry = null;
 
    public function __construct(\Magento\Backend\Block\Widget\Context $context,
								\Magento\Framework\Registry $registry,
								array $data = [])
	{
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    } 
    
    protected function _construct()
    {
        $this->_objectId = 'label_id';
        $this->_blockGroup = 'WDPH_ProductLabels';
        $this->_controller = 'adminhtml_grid'; 
        parent::_construct(); 
        if($this->_isAllowedAction('WDPH_ProductLabels::save'))
		{
            $this->buttonList->update('save', 'label', __('Save Label'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        }
		else
		{
            $this->buttonList->remove('save');
        } 
        if($this->_isAllowedAction('WDPH_ProductLabels::grid_delete'))
		{
            $this->buttonList->update('delete', 'label', __('Delete'));
        }
		else
		{
            $this->buttonList->remove('delete');
        }
 
        if($this->_coreRegistry->registry('labels_grid')->getId())
		{
            $this->buttonList->remove('reset');
        }
    } 
    
    public function getHeaderText()
    {
        if($this->_coreRegistry->registry('labels_grid')->getId())
		{
            return __("Edit Label '%1'", $this->escapeHtml($this->_coreRegistry->registry('labels_grid')->getTitle()));
        }
		else
		{
            return __('New Label');
        }
    } 
    
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('wdph_productlabels/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}