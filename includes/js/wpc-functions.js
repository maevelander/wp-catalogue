jQuery(document).ready(function(){
		jQuery('#prod-title').focus(function() {
			jQuery("#new-prod-label").hide();
        if (this.value == this.defaultValue){
            this.value = '';
        }
        if(this.value != this.defaultValue){
            this.select();
        }
    });
    jQuery('#prod-title').blur(function() {
		if(this.value==''){
			jQuery("#new-prod-label").show();	
		}
        if (this.value == ''){
            this.value = this.defaultValue;
        }
    });
	jQuery('textarea').focus(function() {
        if (this.value == this.defaultValue){
            this.value = '';
        }
        if(this.value != this.defaultValue){
            this.select();
        }
    });
    jQuery('textarea').blur(function() {});


//script to do the basic slidetoggle

	/*jQuery(".wpc-grey-boxes span.close").click(function(){
		jQuery(this).addClass('opened');
		jQuery(this).removeClass('close');
		jQuery(this).parent().removeClass('closed');
	jQuery(".wpc-grey-boxes span.opened").click(function(){
			jQuery(this).removeClass('opened');
			jQuery(this).addClass('close');
			jQuery(this).parent().addClass('closed');
		});
});*/
	
	
	
	
	
//script to upload images


	jQuery('.st_upload_button').click(function() {
		 targetfield = jQuery(this).prev('.upload-url');
		 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 return false;
	});

	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 jQuery(targetfield).val(imgurl);
		 tb_remove();
	}

});
