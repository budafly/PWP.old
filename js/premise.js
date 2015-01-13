jQuery(function($){

	premiseSameHeight();

	premiseMinicolors();

	$(window).resize(function() {
		premiseColumnConform();
	});

	$(window).load( function() {
		premiseColumnConform();
	});

});






function premiseMinicolors() {
	jQuery('.field > .color > input.premise-minicolors').minicolors();
}





/**
 * Premise Same Height
 * @param  {string} el the class of the elements to set same height
 * @return {mixed}     will set same min-height to elements. bool false if unsuccessful
 */
function premiseSameHeight( el ) {
	el = el || '.premise-same-height'
	
	var heightTallest = 0, setHeight;

	var setUp = jQuery( el ).each(function(){
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

	var fixHeight = jQuery( el ).css( 'min-height', heightTallest )

	jQuery.when( setUp ).done( fixHeight )
	
	return false
}

var premiseFileUploader
/**
 * Premise Upload File
 * @param  {object} el button or anchor element to attach action to
 * @return {action}    will open WP file upload functionality
 */
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
    var search = a, Regex = new RegExp(search, "i");
    	
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
	el = typeof el === 'object' ? jQuery(el) : null;

	//if( !el ) console.log( 'You must pass an object for premiseSelectBackground to work' ); return false;

	console.log(el)

	var a       = jQuery(el).val()
	var fadeout = el.parents( '.premise-background-select' ).find( '.premise-background' ).fadeOut('fast')
	var fadein  = el.parents( '.premise-background-select' ).find( '.premise-'+a+'-background' ).fadeIn('fast')

	jQuery.when( fadeout ).done( fadein )

	return false
}







		
// these are (ruh-roh) globals. You could wrap in an
// immediately-Invoked Function Expression (IIFE) if you wanted to...
var premiseCurrentTallest = 0,
    premiseCurrentRowStart = 0,
    premiseRowDivs = new Array();

function premiseSetConformingHeight(el, newHeight) {
	// set the height to something new, but remember the original height in case things change
	el.data("originalHeight", (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight")));
	el.height(newHeight);
}

function premiseGetOriginalHeight(el) {
	// if the height has changed, send the originalHeight
	return (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight"));
}

function premiseColumnConform() {
	var $ = jQuery;
	// find the tallest DIV in the row, and set the heights of all of the DIVs to match it.
	$('#page-wrap > div').each(function() {
	
		// "caching"
		var $el = $(this);
		
		var topPosition = $el.position().top;

		if (premiseCurrentRowStart != topPosition) {

			// we just came to a new row.  Set all the heights on the completed row
			for(currentDiv = 0 ; currentDiv < premiseRowDivs.length ; currentDiv++) premiseSetConformingHeight(premiseRowDivs[currentDiv], premiseCurrentTallest);

			// set the variables for the new row
			premiseRowDivs.length = 0; // empty the array
			premiseCurrentRowStart = topPosition;
			premiseCurrentTallest = premiseGetOriginalHeight($el);
			premiseRowDivs.push($el);

		} else {

			// another div on the current row.  Add it to the list and check if it's taller
			premiseRowDivs.push($el);
			premiseCurrentTallest = (premiseCurrentTallest < premiseGetOriginalHeight($el)) ? (premiseGetOriginalHeight($el)) : (premiseCurrentTallest);

		}
		// do the last row
		for (currentDiv = 0 ; currentDiv < premiseRowDivs.length ; currentDiv++) premiseSetConformingHeight(premiseRowDivs[currentDiv], premiseCurrentTallest);

	});

}





/**
 * Ajax popup
 * 
 * @param  {object} el object or selector for anchor tag with ajax link
 * @return {AJAX}    loads page in anchor tag's href attribute
 */
function premisePopup(el, context) {
	event.preventDefault();

	el = 'undefined' !== typeof el ? jQuery(el) : null
	context = 'undefined' !== typeof context ? context : ''

	var url  = el.attr('href'),
	icon     = jQuery('#premise-ajax-loading'),
	overlay  = jQuery('#premise-ajax-overlay'),
	dialog   = jQuery('#premise-ajax-dialog'),
	close    = jQuery('#premise-ajax-close');

	overlay.fadeIn( 'fast' )
	icon.fadeIn( 'fast' )
	close.fadeIn( 'fast' )

	url     = url + ' ' + context;

	dialog.load( url, function( resp ) {
		dialog.fadeIn('fast')
		icon.fadeOut( 'fast' )
	})

}



/**
 * Close Ajax dialog and empty it.
 * 	
 * @return {bool} false. This function does not return anything
 */
function premiseAjaxClose() {
	var icon = jQuery('#premise-ajax-loading'),
	overlay  = jQuery('#premise-ajax-overlay'),
	dialog   = jQuery('#premise-ajax-dialog'),
	close    = jQuery('#premise-ajax-close');

	icon.fadeOut(    ' fast' )
	overlay.fadeOut( ' fast' )
	dialog.fadeOut(  ' fast' )
	close.fadeOut(   ' fast' )

	dialog.empty()

	return false
}






