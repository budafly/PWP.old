jQuery(function(){

	premiseSameHeight();

});


function premiseSameHeight( el ) {
	el = el || jQuery( '.same-height' )
	
	var heightTallest = 0,setHeight;

	var setUp = el.each(function(){
		if( setHeight )
			return false

		setHeight = jQuery(this).attr('data-height')

		if( setHeight ){
			heightTallest = setHeight
			return false
		}

		var h = jQuery(this).outerHeight()
		if( h > heightTallest ){
			heightTallest = h
		}
	});

	var fixHeight = el.css( 'min-height', heightTallest )

	jQuery.when( setUp ).done( fixHeight )
	
	return false
}

/**
 * Premise Upload File
 * @param  {object} el button or anchor element to attach action to
 * @return {action}    will open WP file upload functionality
 */var premiseFileUploader
function premiseUploadFile( el ){
	el = el || jQuery('.field .file .premise-btn-upload')

    var fileURL = jQuery('.field .file .premise-file-url');

    fileURL.removeClass('insert-img')
    el.siblings('.premise-file-url').addClass('insert-img')
    //If the uploader object has already been created, reopen the dialog
    if (premiseFileUploader) {
        premiseFileUploader.open()
        return
    }
    //Extend the wp.media object
    premiseFileUploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose File',
        button: {
            text: 'Insert File'
        },
        multiple: false
    });
    //When a file is selected, grab the URL and set it as the text field's value
    premiseFileUploader.on('select', function() {
        attachment = premiseFileUploader.state().get('selection').first().toJSON()
        jQuery('.insert-img').val(attachment.url)
    });
    //Open the uploader dialog
    premiseFileUploader.open();
}

/**
 * Premise Remove File
 * @param  {object} el button or anchor element to attach functionality to
 * @return {action}    will clear value of premise-file-url-input
 */
function premiseRemoveFile( el ){
	el = el || jQuery('.field .file .premise-btn-remove')

	el.siblings('.premise-file-url').val('');
	return false;
}

/**
 * [filter description]
 * @param  {string} a string to serach for
 * @return {action}   will filter font-awesome-icons
 */
function premiseFilterIcons(a) {
    var search = a,
    	Regex = new RegExp(search, "i");
    	
	if (!search || '' == search){
		jQuery('.this-icon').parent('li').show();
	}
	else{
		jQuery('.this-icon').parent('li').hide();
		jQuery('.this-icon').each(function(){
			if(jQuery(this).attr('data-icon').search(Regex) > 0){
				jQuery(this).parent('li').show();
			}
		});			
	}   
}

/**
 * Toggle backgrounds ( color, gradient, image)
 * @param  {object} el the object
 * @return {bool}      false
 */
function premiseSelectBackground( el ) {
	el = el || jQuery( '.premise-background-select select' )

	var a = el.val()
	jQuery( '.premise-background' ).fadeOut('fast')
	jQuery( '.premise-'+a+'-background' ).fadeIn('fast')

	return false
}