<?php
namespace WDPH\ProductLabels\Controller\Adminhtml\Index;
 
use Magento\Backend\App\Action;
 
class Save extends \Magento\Backend\App\Action
{    
    protected $cacheTypeList; 
    protected $jsHelper;
    
    public function __construct(Action\Context $context,
								\Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
								\Magento\Backend\Helper\Js $jsHelper)
    {
        $this->cacheTypeList = $cacheTypeList;
        parent::__construct($context);
        $this->jsHelper = $jsHelper;
    } 
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WDPH_ProductLabels::save');
    } 
    
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();        
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data)
		{            
            $model = $this->_objectManager->create('\WDPH\ProductLabels\Model\LabelsGrid');
            $id = $this->getRequest()->getParam('label_id');
            if($id)
			{
                $model->load($id);
            }            
            try
			{
				if (isset($_FILES['image']) && !empty($_FILES['image']['name']) )
				{				
					$uploader = $this->_objectManager->create('\Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'image']);
					$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
					$imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$mediaDirectory = $this->_objectManager->get('\Magento\Framework\Filesystem')->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
					$result = $uploader->save($mediaDirectory->getAbsolutePath('wdph_labels'));
					if($result['error'] == 0)
					{
						$data['image'] = 'wdph_labels/' . $result['file'];
					}					
				}
				else
				{
					if(isset($data['image']) && isset($data['image']['value']))
					{
						if(isset($data['image']['delete']))
						{
							$data['image'] = '';
						}
						elseif(isset($data['image']['value']))
						{
							$data['image'] = $data['image']['value'];
						}
						else
						{
							$data['image'] = '';
						}
					}
				}
				$model->setData($data);
				$this->_eventManager->dispatch(
					'labels_grid_prepare_save',
					['grid' => $model, 'request' => $this->getRequest()]
				); 
                $model->save();
                $this->cacheTypeList->invalidate('full_page');
                $this->messageManager->addSuccess(__('Label saved.'));
                $this->_objectManager->get('\Magento\Backend\Model\Session')->setFormData(false);
                if($this->getRequest()->getParam('back'))
				{
                    return $resultRedirect->setPath('*/*/edit', ['label_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            }
			catch (\Magento\Framework\Exception\LocalizedException $e)
			{
                $this->messageManager->addError($e->getMessage());
            }
			catch (\RuntimeException $e)
			{
                $this->messageManager->addError($e->getMessage());
            }
			catch (\Exception $e)
			{
                $this->messageManager->addException($e, $e->getMessage() /*__('Something went wrong while saving.')*/);
            } 
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['label_id' => $this->getRequest()->getParam('label_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
?>