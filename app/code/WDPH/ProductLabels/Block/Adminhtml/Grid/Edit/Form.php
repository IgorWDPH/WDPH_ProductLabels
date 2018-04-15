<?php 
namespace WDPH\ProductLabels\Block\Adminhtml\Grid\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{    
    protected $_systemStore; 
    protected $_status;
	protected $_attributeFactory;
 
    public function __construct(\Magento\Backend\Block\Template\Context $context,
								\Magento\Framework\Registry $registry,
								\Magento\Framework\Data\FormFactory $formFactory,
								\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory,
								\Magento\Store\Model\System\Store $systemStore,
								array $data = [])
	{
        $this->_systemStore = $systemStore;
		$this->_attributeFactory = $attributeFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid_form');
        $this->setTitle(__('Label Configurations'));
    }
    
    protected function _prepareForm()
    {		
        $model = $this->_coreRegistry->registry('labels_grid');        
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        ); 
        $form->setHtmlIdPrefix('labels_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Label'), 'class' => 'fieldset-wide']
        );
 
        if($model->getId())
		{
            $fieldset->addField('label_id', 'hidden', ['name' => 'label_id']);
        }
 
        $fieldset->addField(
            'label_name',
            'text',
            ['name' => 'label_name', 'label' => __('Label Name'), 'title' => __('Label Name'), 'style' => 'width:250px;', 'required' => true]
        );
        $fieldset->addField(
            'label_text',
            'text',
            ['name' => 'label_text', 'label' => __('Label Text'), 'title' => __('Label Text'), 'required' => false, 'style' => 'width:250px;']
        );
		$fieldset->addField(
            'label_attr',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'label_status',
                'required' => true,
				'style'     => 'width:250px;',				
                'options' => $this->getProductAttributesArray()
            ]
        );
        $fieldset->addField(
            'label_status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'label_status',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
		$fieldset->addField(
            'show_on_product_view_page',
            'select',
            [
                'label' => __('Show on product view page'),
                'title' => __('Show on product view page'),
                'name' => 'show_on_product_view_page',
                'required' => true,
                'options' => ['1' => __('Yes'), '0' => __('No')]
            ]
        );
		$fieldset->addField(
            'show_on_product_list_page',
            'select',
            [
                'label' => __('Show on product list page'),
                'title' => __('Show on product list page'),
                'name' => 'show_on_product_list_page',
                'required' => true,
                'options' => ['1' => __('Yes'), '0' => __('No')]
            ]
        );
		$fieldset->addField(
            'active_from',
            'date',
            [
                'name' => 'active_from',
                'label' => __('Active From'),
                'date_format' => 'MM/dd/yyyy',
				'style'     => 'width:200px;',	
                'time_format' => 'hh:mm:ss'
            ]
        );
		$fieldset->addField(
            'active_to',
            'date',
            [
                'name' => 'active_to',
                'label' => __('Active To'),
                'date_format' => 'MM/dd/yyyy',
				'style'     => 'width:200px;',	
                'time_format' => 'hh:mm:ss'
            ]
        );
		$fieldset->addField(
            'width',
            'text',
            ['name' => 'width', 'label' => __('Label Width'), 'title' => __('Label Width'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'height',
            'text',
            ['name' => 'height', 'label' => __('Label Height'), 'title' => __('Label Height'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'position_top',
            'text',
            ['name' => 'position_top', 'label' => __('Position Top'), 'title' => __('Position Top'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'position_bottom',
            'text',
            ['name' => 'position_bottom', 'label' => __('Position Bottom'), 'title' => __('Position Bottom'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'position_left',
            'text',
            ['name' => 'position_left', 'label' => __('Position Left'), 'title' => __('Position Left'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'position_right',
            'text',
            ['name' => 'position_right', 'label' => __('Position Right'), 'title' => __('Position Right'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'back_color',
            'text',
            ['name' => 'back_color', 'class' => 'wdph-color-picker', 'label' => __('Label Background Color'), 'title' => __('Label Background Color'), 'required' => false, 'style' => 'width:250px;']
        );
		$fieldset->addField(
            'font_color',
            'text',
            ['name' => 'font_color', 'class' => 'wdph-color-picker', 'label' => __('Label Font Color'), 'title' => __('Label Font Color'), 'required' => false, 'style' => 'width:250px;']
        );		
		$fieldset->addField(
			'image',
			'image',
			[
				'title' => __('Background Image'),
				'label' => __('Background Image'),				
				'name' => 'image',
				'note' => 'Allow image type: jpg, jpeg, gif, png'
			]
		);
		$fieldset->addField(
            'custom_css',
            'textarea',
            ['name' => 'custom_css', 'label' => __('Label Custom Styles'), 'title' => __('Label Custom Styles'), 'required' => false]
        );
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
	
	protected function getProductAttributesArray()
	{
		$attributes = $this->_attributeFactory->getCollection()
			->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4)
			->addFieldToFilter('frontend_input', ['in' => ['select', 'boolean']])
			->setOrder('frontend_label','ASC');			
		if(empty($attributes)) return false;
		$result = array();
		$result[0] = 'No attribute';
		foreach($attributes as $attribute)
		{
			$result[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
		}
		return $result;
	}
}
?>