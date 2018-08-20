<?php
namespace WDPH\ProductLabels\Controller\Adminhtml\Index;
 
use Magento\Backend\App\Action;
 
class Save extends \Magento\Backend\App\Action
{    
    protected $cacheTypeList; 
    protected $jsHelper;
	protected $labelsMainHelper;
	protected $labelsFactory;
    
    public function __construct(Action\Context $context,
								\Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
								\Magento\Backend\Helper\Js $jsHelper,
								\WDPH\ProductLabels\Model\ResourceModel\LabelsGrid\CollectionFactory $labelsFactory,
								\WDPH\ProductLabels\Helper\Data $labelsMainHelper)
    {
		$this->labelsMainHelper = $labelsMainHelper;
        $this->cacheTypeList = $cacheTypeList;
        $this->labelsFactory = $labelsFactory;
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
        if($data)
		{
			/*$fl = fopen('/var/www/bravo/tst.txt', 'w+');
			fwrite($fl, print_r($data, true));
			fclose($fl);*/
            $model = $this->_objectManager->create('\WDPH\ProductLabels\Model\LabelsGrid');
            $id = $this->getRequest()->getParam('label_id');
            if($id)
			{
                $model->load($id);
            }
            else
            {
	            /*$fl = fopen('/var/www/bravo/tst.txt', 'w+');
	            fwrite($fl, $model->isSaveAllowed() . '#' . $model->isObjectNew());
	            fclose($fl);*/
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
					$result = $uploader->save($mediaDirectory->getAbsolutePath($this->labelsMainHelper->getLabelsMediaDir()));
					if($result['error'] == 0)
					{
						$data['image'] = $this->labelsMainHelper->getLabelsMediaDir() . DIRECTORY_SEPARATOR . $result['file'];
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
				$data['store_ids'] = implode(',', $data['store_ids']);
				$model->setData($data);
				/*$this->_eventManager->dispatch(
					'wdph_productlabels',
					['grid' => $model, 'request' => $this->getRequest()]
				);*/
				//$model->save();
				$this->labelsFactory->create()->save($model);
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