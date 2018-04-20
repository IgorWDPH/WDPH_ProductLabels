<?php
namespace WDPH\ProductLabels\Block\System\Config;

class Color extends \Magento\Config\Block\System\Config\Form\Field
{    
    public function __construct(\Magento\Backend\Block\Template\Context $context, array $data = [])
	{        
        parent::__construct($context, $data);
    }
    
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {        
        $html = $element->getElementHtml();
        $html .= '<script type="text/javascript">
					require(["jquery"], function ($)
					{
						$(document).ready(function()
						{		
							$(\'#' . $element->getHtmlId() . '\').each(function(index)
							{
								var parentThis = this;
								var id = \'colorpicker\' + $(this).attr(\'id\');
								$(this).after(\'<div class="colorpicker-circle" id="\' + id + \'"></div>\');			
								$(\'#\' + id).farbtastic($(this));

								$(document).on(\'click\', function()
								{
									if($(event.target).closest($(parentThis)).length || $(event.target).closest($(\'#\' + id)).length)
									{
										if(!$(\'#\' + id).hasClass(\'show\'))
										{
											$(\'#\' + id).addClass(\'show\');
										}
									}
									else
									{					
										if($(\'#\' + id).hasClass(\'show\'))
										{
											$(\'#\' + id).removeClass(\'show\');
										}
									}
								});			
							});		
						});
					});
					</script>';
        return $html;
    }
}
?>