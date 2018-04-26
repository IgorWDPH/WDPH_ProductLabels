<?php 
namespace WDPH\ProductLabels\Block\Adminhtml\Grid\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{    
    protected $_systemStore; 
    protected $_status;
	protected $_attributeFactory;
	protected $labelsMainHelper;
 
    public function __construct(\Magento\Backend\Block\Template\Context $context,
								\Magento\Framework\Registry $registry,
								\Magento\Framework\Data\FormFactory $formFactory,
								\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory,
								\Magento\Store\Model\System\Store $systemStore,								
								\WDPH\ProductLabels\Helper\Data $labelsMainHelper,								
								array $data = [])
	{
		$this->labelsMainHelper = $labelsMainHelper;
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
            'label_manual',
            'note',
            ['name' => 'label_manual', 'text' => '<h1>!!!ATTENTION!!!</h1>
													<ul>
														<li>Label Name is just an identifier 4 u, u\'ll see it on the labels grid page;</li>
														<li>U may use two types of attributes - select and boolean;</li>
														<li>If boolean attribute chosen then Label Text will be shown on frontend page if product has current attribute set to Yes;</li>
														<li>If select attribute chosen then selected attribute value will be shown if it set for current product; </li>
														<li>If </li>
													</ul>
													<style type="text/css">
													#labels_label_manual { width: 100%;
																			margin: 0 50px;
																			padding: 15px;
																			border: 6px dashed;
																			background-color: #fffbbb; }
													#labels_label_manual h1 { font-weight: 800;
																				text-align: center;
																				color: #f00;}
													#labels_label_manual ul li { list-style: none; }
													</style>']
        ); 
        $fieldset->addField(
            'label_name',
            'text',
            ['name' => 'label_name', 'label' => __('Label Name'), 'title' => __('Label Name'), 'style' => 'width:250px;', 'required' => true]
        );
		$fieldset->addField(
		   'store_ids',
		   'multiselect',
		   [
			 'name'     => 'store_ids',
			 'label'    => __('Store Views'),
			 'title'    => __('Store Views'),
			 'required' => true,
			 'values'   => $this->_systemStore->getStoreValuesForForm(false, true)
		   ]
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
                'name' => 'label_attr',
                'required' => false,
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
            'new_label',
            'select',
            [
                'label' => __('New Product Label'),
                'title' => __('New Product Label'),
                'name' => 'new_label',
                'required' => true,
                'options' => ['1' => __('Yes'), '0' => __('No')]
            ]
        );
		$fieldset->addField(
            'sale_label',
            'select',
            [
                'label' => __('Sale Product Label'),
                'title' => __('Sale Product Label'),
                'name' => 'sale_label',
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
            'width_list',
            'text',
            ['name' => 'width_list', 'label' => __('Product List Label width'), 'title' => __('Product List Label width'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'height_list',
            'text',
            ['name' => 'height_list', 'label' => __('Product List Label height'), 'title' => __('Product List Label height'), 'required' => false, 'style' => 'width:100px;']
        );		
		
		$fieldset->addField(
            'width_view',
            'text',
            ['name' => 'width_view', 'label' => __('Product View Label width'), 'title' => __('Product View Label width'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'height_view',
            'text',
            ['name' => 'height_view', 'label' => __('Product View Label width'), 'title' => __('Product View Label width'), 'required' => false, 'style' => 'width:100px;']
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
            'list_font_size',
            'text',
            ['name' => 'list_font_size', 'label' => __('Product List Label Font Size'), 'title' => __('Product List Label Font Size'), 'required' => false, 'style' => 'width:100px;']
        );
		$fieldset->addField(
            'view_font_size',
            'text',
            ['name' => 'view_font_size', 'label' => __('Product View Label Font Size'), 'title' => __('Product View Label Font Size'), 'required' => false, 'style' => 'width:100px;']
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
		$result[''] = 'No attribute';
		foreach($attributes as $attribute)
		{
			$result[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
		}
		return $result;
	}
}
?>