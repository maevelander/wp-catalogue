jQuery(document).ready(function(){
	
	
	jQuery(".new-prdct-img img").click(function(){
		var cID	=	jQuery(this).attr('id');
		jQuery('.product-img-view img').hide();
		jQuery('.product-img-view img#'+cID).fadeIn(500);
		
	});
	

});




