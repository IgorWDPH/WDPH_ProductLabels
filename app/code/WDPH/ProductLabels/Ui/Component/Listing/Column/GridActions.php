<?php
namespace WDPH\ProductLabels\Ui\Component\Listing\Column;
 
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
 
class GridActions extends Column
{  
	const GRID_URL_PATH_EDIT = 'wdph_productlabels/index/edit';
	const GRID_URL_PATH_DELETE = 'wdph_productlabels/index/delete';
	
	protected $urlBuilder; 
	private $editUrl;
 
	public function __construct( ContextInterface $context,
							  UiComponentFactory $uiComponentFactory,
							  UrlInterface $urlBuilder,
							  array $components = [],
							  array $data = [],
							  $editUrl = self::GRID_URL_PATH_EDIT )
	{
		$this->urlBuilder = $urlBuilder;
		$this->editUrl = $editUrl;
		parent::__construct($context, $uiComponentFactory, $components, $data);
	}
 
	public function prepareDataSource(array $dataSource)
	{
		if(isset($dataSource['data']['items']))
		{
			foreach($dataSource['data']['items'] as & $item)
			{
				$name = $this->getData('name');
				if(isset($item['label_id']))
				{
					$item[$name]['edit'] = [
						'href' => $this->urlBuilder->getUrl($this->editUrl, ['label_id' => $item['label_id']]),
						'label' => __('Edit')
					];
					$item[$name]['delete'] = [
						'href' => $this->urlBuilder->getUrl(self::GRID_URL_PATH_DELETE, ['label_id' => $item['label_id']]),
						'label' => __('Delete'),
						'confirm' => [
						  'title' => __('Delete label with ID: "${ $.$data.label_id }"'),
						  'message' => __('Are you sure you wan\'t to delete a label with ID "${ $.$data.label_id }"?')
						]
					];
				}
			}
		}
		return $dataSource;
	}
}
?>