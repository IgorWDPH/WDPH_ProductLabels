require(['jquery'], function($, gallery)
{
	$('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function ()
	{
		$(this).on('fotorama:ready', function()
		{
			$('.wdph-product-label').each(function()
			{
				var attrBottom = $(this).attr('bottom');				
				if(typeof attrBottom !== typeof undefined && attrBottom !== false)
				{
					console.log($('.product.media .gallery-placeholder .fotorama__wrap').innerHeight());
				}
			});
			console.log('gallery ready!');
		});
	});
});