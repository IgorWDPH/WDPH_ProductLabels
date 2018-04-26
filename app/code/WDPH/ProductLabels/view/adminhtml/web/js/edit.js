require(['jquery'], function($, gallery)
{
	$(document).ready(function()
	{		
		$('#labels_new_label').on('change', function()
		{			
			if($('#labels_new_label').val() == 1)
			{
				$('#labels_sale_label').val(0);
			}
		});
		$('#labels_sale_label').on('change', function()
		{			
			if($('#labels_new_label').val() == 1)
			{
				$('#labels_new_label').val(0);
			}
		});
	});
});