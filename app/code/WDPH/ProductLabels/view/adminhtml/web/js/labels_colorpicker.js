require(["jquery"], function ($)
{
	$(document).ready(function()
	{		
		$('.wdph-color-picker').each(function(index)
		{
			var parentThis = this;
			var id = 'colorpicker' + $(this).attr('id');
			$(this).after('<div class="colorpicker-circle" id="' + id + '"></div>');			
			$('#' + id).farbtastic($(this));

			$(document).on('click', function()
			{
				if($(event.target).closest($(parentThis)).length || $(event.target).closest($('#' + id)).length)
				{
					if(!$('#' + id).hasClass('show'))
					{
						$('#' + id).addClass('show');
					}
				}
				else
				{					
					if($('#' + id).hasClass('show'))
					{
						$('#' + id).removeClass('show');
					}
				}
			});			
		});		
	});
});