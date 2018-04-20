<?php
namespace WDPH\ProductLabels\Controller\Adminhtml\Index;
 
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action;
 
class Delete extends \Magento\Backend\App\Action
{    
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    } 
    
    public function execute()
    {
        $id = $this->getRequest()->getParam('label_id');		
        if($id)
		{			
            try
			{                
                $model = $this->_objectManager->create('WDPH\ProductLabels\Model\LabelsGrid');
                $model->load($id);
                $model->delete();
                $this->_redirect('wdph_productlabels/*/');
                $this->messageManager->addSuccess(__('Label deleted successfully.'));
                return;
            }
			catch(LocalizedException $e)
			{
                $this->messageManager->addError($e->getMessage());
            }
			catch(\Exception $e)
			{
                $this->messageManager->addError(
                    __('Something went wrong :(')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('wdph_productlabels/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('Can\'t find label ID :(.'));
        $this->_redirect('wdph_productlabels/*/');
    }
}
?>