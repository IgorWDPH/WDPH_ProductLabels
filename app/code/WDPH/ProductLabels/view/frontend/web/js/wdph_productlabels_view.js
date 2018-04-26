require(['jquery'], function($, gallery)
{	
	$('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function ()
	{
		$(this).on('fotorama:ready', function()
		{
			var bottom = $('.fotorama__wrap').innerHeight() - $('.fotorama__wrap .fotorama__stage').height();			
			$('.wdph-product-label').each(function()
			{
				var attrBottom = $(this).attr('bottom');
				if(typeof attrBottom !== typeof undefined && attrBottom !== false)
				{
					$(this).css('bottom', parseInt($(this).attr('bottom'), 10) + bottom + 'px');
				}
			});			
		});
	});
});