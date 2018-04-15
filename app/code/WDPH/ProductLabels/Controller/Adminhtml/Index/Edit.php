<?php
namespace WDPH\ProductLabels\Controller\Adminhtml\Index;
 
use Magento\Backend\App\Action;
 
class Edit extends \Magento\Backend\App\Action
{    
    protected $_coreRegistry = null; 
    protected $resultPageFactory;
 
    public function __construct(Action\Context $context,
								\Magento\Framework\View\Result\PageFactory $resultPageFactory,
								\Magento\Framework\Registry $registry)
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WDPH_ProductLabels::save');
    }

    protected function _initAction()
    {        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('WDPH_ProductLabels::grid');
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('label_id');       
        $model = $this->_objectManager->create('WDPH\ProductLabels\Model\LabelsGrid');
        if($id)
		{
            $model->load($id);
            if (!$model->getId())
			{
                $this->messageManager->addError(__('This label no longer exists.'));                
                $resultRedirect = $this->resultRedirectFactory->create(); 
                return $resultRedirect->setPath('*/*/');
            }
        } 
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if(!empty($data))
		{
            $model->setData($data);
        } 
        $this->_coreRegistry->register('labels_grid', $model);
        $resultPage = $this->_initAction();        
        $resultPage->getConfig()->getTitle()->prepend(__('Labels'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Label'));
        return $resultPage;
    }
}
?>